

/*Payment option based on the payment method selected*/
/*Payment account option - EXTEND PaymentMethod option JS*/
function paymentAccountOption(payment_account_id,  bank_input_id)
{
    var check_bank_disabled = $('#' + bank_input_id).prop('disabled');
    if(check_bank_disabled){
        $('#' + payment_account_id).attr('disabled', false);
    }else{
        $('#' + payment_account_id).attr('disabled', true);
        $('#' + payment_account_id).val('').change();
    }
}

