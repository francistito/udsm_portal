function confirmAlert(message_alert, title = 'Are You Sure?', yes_label = null, no_label = null){
    $('#confirmTitleLabel').text(title).change();
    $('#confirmAlertLabel').text(message_alert).change();

    yes_label = (yes_label) ? yes_label : 'Confirm';
    no_label = (no_label) ? no_label : 'Cancel';
    $('#modal-btn-yes').text(yes_label).change();
    $('#modal-btn-no').text(no_label).change();

    $("#confirmAlertModal").modal('show');
}

// var modalConfirm = function(callback){
//
//     $("#btn-confirm").on("click", function(){
//         $("#confirmAlertModal").modal('show');
//     });
//
//     $("#modal-btn-yes").on("click", function(){
//         callback(true);
//         $("#confirmAlertModal").modal('hide');
//     });
//
//     $("#modal-btn-no").on("click", function(){
//         callback(false);
//         $("#confirmAlertModal").modal('hide');
//     });
// };
//
// modalConfirm(function(confirm){
//     if(confirm){
//         return true;
//     }else{
//         return false;
//     }
// });



function confirmAction()
{
    $("#modal-btn-yes").on("click", function(){
        return true;
    });

    $("#modal-btn-no").on("click", function(){
        return false;
    });
}