<?php
/**
 * Created by PhpStorm.
 * User: dontito
 * Date: 6/27/19
 * Time: 12:44 PM
 */

namespace App\Repositories\Payment;

use App\Events\Payment\PaidInvoice;
use App\Exceptions\GeneralException;
use App\Models\Payment\Invoice;
use App\Repositories\BaseRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Services\Payment\Nextpay\NextPayProcessPayment;
use App\Services\Payment\Selcom\ProcessSelcom;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceRepository extends BaseRepository
{

    const MODEL = Invoice::class;

    public function __construct()
    {

    }

    public function checkPendingInvoice($customerId)
    {
        $invoice = $this->query()->where(["customer_id" => $customerId, "iscancelled" => 0, "ispaid" => 0])->orderByDesc("id")->first();
        return $invoice;
    }

    /**
     * @param $responseArr
     * @param $invoice
     * @return array
     */
    public function getCreatedPaymentInput($responseArr, $invoice)
    {
        $input = [];
        switch ($invoice->source_id) {
            case 6:
                //Selcom
                $phone = NULL;
                if (isset($responseArr['phone'])) {
                    try {
                        $phone = phone($responseArr['phone'] , $country = ['TZ'], $format = 'E164');
                    } catch (\Exception $e) {
                        $phone = $responseArr['phone'];
                    } catch (\Throwable $e) {
                        $phone = $responseArr['phone'];
                    }
                }
                $input = [
                    'reference' => $responseArr['reference'],
                    'amount' => (isset($responseArr['amount'])) ? $responseArr['amount'] : $invoice->amount,
                    'invoice_id' => $invoice->id,
                    'currency_id' => $invoice->currency_id,
                    'result' => '',
                    'operator' => (isset($responseArr['channel'])) ? $responseArr['channel'] : NULL,
                    'transid' => $responseArr['transid'],
                    'utility_ref' => NULL,
                    'external_reference' => $responseArr['reference'],
                    'source_id' => $invoice->source_id,
                    'msisdn' => $phone,
                    'status' => $responseArr['payment_status'],
                ];
                break;
                case 8:
                //Nextpay
                $phone = NULL;
                if (isset($responseArr['CustomerMSISDN'])) {
                    try {
                        $phone = phone($responseArr['CustomerMSISDN'] , $country = ['TZ'], $format = 'E164');
                    } catch (\Exception $e) {
                        $phone = $responseArr['CustomerMSISDN'];
                    } catch (\Throwable $e) {
                        $phone = $responseArr['CustomerMSISDN'];
                    }
                }
                $input = [
                    'reference' => $responseArr['CustomerRefId'],
                    'amount' => (isset($responseArr['amount'])) ? $responseArr['amount'] : $invoice->amount,
                    'invoice_id' => $invoice->id,
                    'currency_id' => $invoice->currency_id,
                    'result' => '',
                    'operator' => (isset($responseArr['channel'])) ? $responseArr['channel'] : NULL,
                    'transid' => $invoice->reference,
                    'utility_ref' => NULL,
                    'external_reference' => $responseArr['TigoMFSId'],
                    'source_id' => $invoice->source_id,
                    'msisdn' => $phone,
                    'status' => $responseArr['Message'],
                ];
                break;
        }
        return $input;
    }

    public function paymentconfirmation($source, array $input, $request)
    {
        Log::info($source->id);
        $return = response()->json(['response' => "OK"]);
        switch ($source->id) {
            case 7:
                //Pesapal
                $pesapal_transaction_tracking_id = $input['pesapal_transaction_tracking_id'];
                $pesapal_merchant_reference = $input['pesapal_merchant_reference'];

                $invoice = $this->query()->where('uuid', $pesapal_merchant_reference)->first();

                $payment = (new PaymentRepository())->pesapalConfirm($request, $pesapal_transaction_tracking_id, $pesapal_merchant_reference, $invoice, $source);

                $return = redirect()->route('payment.invoice', $invoice->uuid)->withFlashSuccess(__('alerts.service.invoice.paid'));
                break;
            case 6:
                //Selcom
                logger($input);
                $invoice = $this->query()->where(["number" => $input['order_id']])->first();
                if ($invoice) {
                    logger($input);
                    $responseArr = $input;
                    if (count($responseArr) And isset($responseArr['result'])) {
                        if ($responseArr['result'] == "SUCCESS") {
                            switch ($responseArr['payment_status']) {
                                case "COMPLETED":
                                    if (!$invoice->ispaid) {
                                        $invoice->ispaid = 1;
                                        $invoice->save();
                                        //Pay Invoice
                                        $input = $this->getCreatedPaymentInput($responseArr, $invoice);
                                        $payment = (new PaymentRepository())->store($input);
                                        //Credit Customer
                                        event(new PaidInvoice($invoice));
                                    }
                                    break;
                            }
                        }
                    }
                }
                $return = response()->json(['response' => "OK"]);
                break;
            case 8:
                //Nextpay
                logger($input);
                $invoice = $this->query()->where(["number" => $input['CustomerRefId']])->first();
                if ($invoice) {
                    logger($input);
                    $responseArr = $input;
                    if (count($responseArr) And isset($responseArr['CustomerRefId'])) {
                        if ($responseArr['CustomerRefId'] == $invoice->number) {
//                            switch ($responseArr['payment_status']) {
//                                case "COMPLETED":
                                    if (!$invoice->ispaid) {
                                        $invoice->ispaid = 1;
                                        $invoice->message = $responseArr['Message'];
                                        $invoice->payment_status = "Paid";
                                        $invoice->order = json_encode($responseArr);
                                        $invoice->save();
                                        //Pay Invoice
                                        $input = $this->getCreatedPaymentInput($responseArr, $invoice);
                                        $payment = (new PaymentRepository())->store($input);
                                        //Credit Customer
                                        event(new PaidInvoice($invoice));
//                                    }
//                                    break;
                            }
                        }
                    }
                }
                $return = response()->json(['Status' => 'Accepted', 'Message' => 'Callback received successfully']);
                break;
        }
        return $return;
    }

    /**
     * @param Model $invoice
     * @return string
     * @throws GeneralException
     */
    public function checkInvoicePayment(Model $invoice)
    {
        $message = "";
        switch ($invoice->source_id) {

            case 8:
                //Nextpay
                $nextpay = new NextPayProcessPayment();
                if (isset($invoice->payment))
                {
//                    $response = $nextpay->status($invoice->payment->msisdn, $invoice->number);
//                    logger($response);
//                    $responseArray = json_decode($response, true);
                    if (!$invoice->ispaid)
                    {
//                        switch ($responseArray['Status'])
//                        {
//                            case "Accepted":
                                $message = "Invoice has already been paid";
//                                if (!$invoice->ispaid) {
                                    $invoice->ispaid = 1;
                                    $invoice->save();
                                    event(new PaidInvoice($invoice));
//                                }
//                                break;
//                            default:
//                                $message = "Processing";
//                                break;

//                        }
                    }
                    elseif ($invoice->ispaid)
                    {
                        $message = "Invoice has already been paid";
                    }
                    else {
                        throw new GeneralException("Sorry, your request can not be processed now, please try again later ...");
                    }
                }
                else {
                    throw new GeneralException("Sorry, your request can not be processed now, please try again later ...");
                }
                break;
            default:
                break;
        }
        return $message;
    }

    public function store(array $input, $customer,$total_amount)
    {
        $customer_id = $customer->id;
        return DB::transaction(function () use ($input, $customer,$total_amount,$customer_id) {
            $invoice = Invoice::where('customer_id',$customer_id)->where('ispaid',0)->where('iscancelled',0)->first();
            if ($invoice)
            {
                return $invoice;
            }else{
                $invoiceData = [
                    'customer_id' =>$customer_id ,
                    'product_id' =>$input['product_id'],
                    'currency_id' =>1,
                    'amount' => $total_amount,
                    'mature_date' => Carbon::today(),
                    'package_services' => json_encode($input['package_services']),
                ];
                if (count($invoiceData)) {
                    $invoice = $this->query()->create($invoiceData);
                    return $invoice;
                }
            }

        });

    }

    public function getNextInvoiceSysdef()
    {
        $sysdef = sysdef()->definition("INVNUMB");
        $nextInvoice = $sysdef->value;
        $sysdef->value = $nextInvoice + 1;
        $sysdef->save();
        $nextInvoiceno = checksum($nextInvoice, sysdef()->name("invoice_number_length"));
        return $nextInvoiceno;
    }


    public function getInvoiceForDatatable(){
        $customer = access()->customer();
        if ($customer)
        {
            return $this->query()
                ->where('customer_id',$customer->id)
                ->where('iscancelled',0)->orderBy('created_at',"DESC");
        }

    }
    public function getFilteredInvoiceForDatatable($filters){
        $customer = access()->customer();
        if ($customer)
        {
            if (isset($filters->is_paid))
            {
                return $this->query()
                    ->where('customer_id',$customer->id)
                    ->where('ispaid', $filters->is_paid)
                    ->whereBetween('created_at', [$filters->start_date, $filters->end_date])
                    ->where('iscancelled',0)->orderBy('created_at',"DESC");
            }
            else{
                return $this->query()
                    ->where('customer_id',$customer->id)
                    ->whereBetween('created_at', [$filters->start_date, $filters->end_date])
                    ->where('iscancelled',0)->orderBy('created_at',"DESC");
            }
        }

    }

    /**
     * @param $invoice
     * @return mixed
     */
    public function cancelInvoice($invoice, $isjobcall = 0)
    {
        $message = null;
        return DB::transaction( function () use ($invoice, $isjobcall, $message) {
            $return = ["success" => false, "message" => ""];
                    //Nextpay
                    $message = __('alerts.service.invoice.cancelled');
                    $invoice->update([
                        'iscancelled' => 1
                    ]);
                    $return["success"] = true;
            $return["message"] = $message;
            return $return;
        });
    }

    public function getInvoicesForApi($q)
    {
        return DB::table('invoices')
            ->select([
                DB::raw('invoices.uuid AS uuid'),
                DB::raw('invoices.number AS invoice_number'),
                DB::raw("(CASE WHEN (invoices.ispaid = 1) THEN 'Paid' ELSE 'Pending' END) AS status"),
                DB::raw('invoices.recharge_quantity AS recharge_quantity'),
                DB::raw('invoices.amount AS total_amount'),
                DB::raw('invoices.created_at AS issued_on')
            ])
            ->where("customer_id", access()->customerId())
            ->where('iscancelled',0)
            ->where(function ($query) use ($q) {
                if ($q) {
                    $query->whereRaw('invoices.number::varchar ~* ? or invoices.recharge_quantity::varchar ~* ? or invoices.amount::varchar ~* ? or invoices.created_at::varchar ~* ? or (CASE WHEN (invoices.ispaid = 1) THEN \'Paid\' ELSE \'Pending\' END) ~* ?', [$q, $q, $q, $q, $q]);
                } else {
                    $query->whereRaw("'1'");
                }
            })
            ->orderByDesc('invoices.created_at')
            ->paginate(10)
            ->toArray();
    }

    public function getAllInvoices()
    {
        return $this->query()->orderByDesc('created_at')->where('iscancelled', 0);
    }

    public function getAllCustomerInvoices(Model $customer)
    {
        return $this->query()->orderByDesc('created_at')->where('customer_id', $customer->id)->where('iscancelled', 0);
    }

}
