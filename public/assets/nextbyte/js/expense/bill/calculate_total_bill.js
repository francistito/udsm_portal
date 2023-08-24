
var net_to_pay_global = 0;
/*calculate total bill*/
function calculateTotalBill()
{

    var bill_pay_amount = 0;
    var total_bill = 0;
    var credit_amount = checkIfElementIsset('total_vendor_credit_applied') ? element_id_value('total_vendor_credit_applied') : '0';//todo vendor credit
    credit_amount = parseFloat(removeThousandSeparator(credit_amount));

    var net_amount = 0;
    var element_id;
    var bill_max_payable = 0;
    var expense_id = null;
    max_payable_bill_amount = 0;
    $( ".calculate_total_bill" ).each(function(e) {
        element_id = this.id;
        expense_id  = element_id.substr(19);
        bill_max_payable = element_id_value('balance_amount_max' + expense_id);
        bill_max_payable = parseFloat(removeThousandSeparator(bill_max_payable));
        bill_pay_amount = element_id_value(element_id);
        bill_pay_amount = parseFloat(removeThousandSeparator(bill_pay_amount));
        /*total bill for this*/
        total_bill = bill_pay_amount + total_bill;
        /*get max bill payable overall*/
        max_payable_bill_amount = bill_max_payable + max_payable_bill_amount;
    });

    net_amount = total_bill - credit_amount;
    net_to_pay_global = net_amount;

    /*put on the input*/
    $('#total_bill').text(accountingNumberPresentation(total_bill));
    $('#net_amount_to_pay').text(accountingNumberPresentation(net_amount));

    /*credit variable*/
    $('#credit_amount').text(accountingNumberPresentation((-1 * credit_amount)));
    $('#total_vendor_credit_applied').text(accountingNumberPresentation(credit_amount));

    /*top amount universal - expense amount*/
    // if(checkIfElementIsChecked('checkbox_net_amount') == false){
        /*update top only if check box for net reference*/
        $('#total_amount_top').text('Amount: ' + accountingNumberPresentation(net_amount));
        $('#net_expense_amount').val(accountingNumberPresentation(net_amount));
        $('#total_bill_payable').val(accountingNumberPresentation(total_bill));
    // }

    /*Validate net pay*/
    validationOnTotalPayableAmount();
}


/*Validate on total payable amount*/
function validationOnTotalPayableAmount()
{
    /*net amount to pay based on outstanding bills/credit*/
    var net_amount_to_pay =net_to_pay_global;

    /*net expense amount reference on top*/
    var net_expense_amount = $('#net_expense_amount').val();
    net_expense_amount =  parseFloat(removeThousandSeparator(net_expense_amount));
    var unallocated_amount = net_amount_to_pay - net_expense_amount;
    unallocated_amount = Math.abs(unallocated_amount);

    if(unallocated_amount > 0.1 || net_amount_to_pay < 0){
        $('#net_to_pay_error').text('Amount to pay should tally with the amount declared. Unallocated Amount: '+ accountingNumberPresentation(unallocated_amount) ).change();
        hide_show('hide_class','submit_button_save')
    }else{
        $('#net_to_pay_error').text('').change();
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