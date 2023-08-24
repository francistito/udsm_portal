


function getDueDateBasedOnSelectedTerm(element_term_id, element_trans_date_id,element_due_date_id, old_date_format = 0) {

 if(checkIfElementIsset(element_term_id)){
     var term_id = element_id_value(element_term_id);
     var trans_date = element_id_value(element_trans_date_id);

     posting = $.post( url + "/setting/payment_term/get_due_date_term_selected_ajax" , {
         payment_term_id: term_id,
         trans_date: trans_date,
         _method : "GET" } );
     posting.done(function( data ) {
         //Action/Response
         if(old_date_format == '0'){
             $('#'+element_due_date_id).val(data.due_date).change();
         }else{
             $('#'+element_due_date_id).val(data.due_date_old_format).change();
         }

     });

 }




}