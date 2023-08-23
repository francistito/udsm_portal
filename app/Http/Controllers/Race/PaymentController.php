<?php

namespace App\Http\Controllers\Race;

use App\Http\Controllers\Controller;
use App\Models\RaceCategory;
use App\Repositories\Payment\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    protected $payment_repo;
    public function __construct()
    {
        $this->payment_repo = new PaymentRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function makePayment($race_registration,Request $request)
    {

        $payment = $this->payment_repo->expertenPayment($race_registration,$request);
        return redirect()->route('race.registration');
    }


}
