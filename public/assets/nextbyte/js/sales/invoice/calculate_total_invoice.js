
var net_to_receive_global = 0;
/*calculate total invoice*/
function calculateTotalInvoice()
{

    var invoice_received_amount = 0;
    var total_invoice = 0;
    var credit_amount = checkIfElementIsset('total_credit_memo_applied') ? element_id_value('total_credit_memo_applied') : '0';//todo credit
    credit_amount = parseFloat(removeThousandSeparator(credit_amount));

    var net_amount = 0;
    var element_id;
    var invoice_max_payable = 0;
    var sale_id = null;
    max_payable_invoice_amount = 0;
    $( ".calculate_total_invoice" ).each(function(e) {
        element_id = this.id;
        sale_id  = element_id.substr(22);

        invoice_max_payable = element_id_value('balance_amount_max' + sale_id);
        invoice_max_payable = parseFloat(removeThousandSeparator(invoice_max_payable));
        invoice_pay_amount = element_id_value(element_id);
        invoice_pay_amount = parseFloat(removeThousandSeparator(invoice_pay_amount));
        /*total invoice for this*/
        total_invoice = invoice_pay_amount + total_invoice;
        /*get max invoice payable overall*/
        max_payable_invoice_amount = invoice_max_payable + max_payable_invoice_amount;
    });

    net_amount = total_invoice - credit_amount;
    net_to_receive_global = net_amount;


    /*put on the input*/
    $('#total_invoice').text(accountingNumberPresentation(total_invoice));
    $('#net_amount_to_receive').text(accountingNumberPresentation(net_amount));

    /*credit variable*/
    $('#credit_amount').text(accountingNumberPresentation((-1 * credit_amount)));
    $('#total_credit_memo_applied').text(accountingNumberPresentation(credit_amount));

    /*top amount universal - sale amount*/
    // if(checkIfElementIsChecked('checkbox_net_amount') == false){
    /*update top only if check box for net reference*/
    $('#total_amount_top').text('Amount: ' + accountingNumberPresentation(net_amount));
    $('#net_sale_amount').val(accountingNumberPresentation(net_amount));
    $('#total_sale_payable').val(accountingNumberPresentation(total_invoice));
    // }

    /*Validate net pay*/
    validationOnTotalPayableAmount();
}


/*Validate on total payable amount*/
function validationOnTotalPayableAmount()
{
    /*net amount to pay based on outstanding invoice/credit*/
    var net_amount_to_receive =parseFloat(net_to_receive_global);

    /*net sale amount reference on top*/
    var net_sale_amount = $('#net_sale_amount').val();
    net_sale_amount =  parseFloat(removeThousandSeparator(net_sale_amount));

    var unallocated_amount = net_amount_to_receive - net_sale_amount;
      unallocated_amount = Math.abs(unallocated_amount);

    if(unallocated_amount > 0.1 || net_amount_to_receive < 0){

        $('#net_to_receive_error').text('Amount to receive should tally with the amount declared. Unallocated Amount: '+ unallocated_amount ).change();
        hide_show('hide_class','submit_button_save')
    }else{
        $('#net_to_receive_error').text('').change();
        hide_show('show_class','submit_button_save')
    }
}



/*reset exceed max amount*/
function resetIfExceedMaximum(element_id,max_amount, amount_inserted )
{
    if(amount_inserted > max_amount){
        $('#'+element_id).val(accountingNumberPresentation(max_amount));
    }
}