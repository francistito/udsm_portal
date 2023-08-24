

/*Bank option based on the payment method selected*/
function paymentMethodOptionGeneral(payment_method_id,  payment_reference_element_id)
{
if(payment_method_id != '' && payment_method_id != null){
    /*Save*/
    posting = $.post( base_url + "/setting/payment_method/get_details_ajax" , {
        // client_id: client_id,
        payment_method_id: payment_method_id,

        _method : "GET" } );
    posting.done(function( data ) {
        //Action/Response
        paymentReferenceOptionGen(data.need_reference, payment_reference_element_id)
    });
}


}


function paymentReferenceOptionGen(need_reference,payment_reference_element_id)
{
    /*Options*/
    $("#label_" + payment_reference_element_id).removeClass("required_asterik");
    $("#" + payment_reference_element_id).removeClass("required");

    if(need_reference == '1'){
        $("#label_" + payment_reference_element_id).addClass("required_asterik");
        $("#" + payment_reference_element_id).addClass("required");
    }



}


