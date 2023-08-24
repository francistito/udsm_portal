
/*Fund transfer before submit*/
function fundTransferBeforeSubmit(je_batch_type_ref = 'JEBTFTRN')
{
    var from_account_id =  element_id_value('from_account_id');
    var to_account_id =  element_id_value('to_account_id');
    var isincrease;

    /*From account id*/
    if(from_account_id != null && from_account_id != ''){
        switch(je_batch_type_ref){
            case 'JEBTFTRN':
                isincrease = 0;
                break;

            case 'JEBTOWDEP':
            case 'JEBTLNDP':

                isincrease = 1;
                break;

            case 'JEBTOWDRW':
                isincrease = 0;
                break;

            default:
                isincrease = 0;
                break;
        }


        createNormalJournalEntriesInputForTransfer(from_account_id, 1, isincrease);
    }

    /*To account id*/
    if(to_account_id != null && to_account_id != ''){

        switch(je_batch_type_ref){
            case 'JEBTFTRN':
                isincrease = 1;
                break;
            case 'JEBTOWDEP':
            case 'JEBTLNDP':
                isincrease = 1;
                break;

            case 'JEBTOWDRW':
                isincrease = 0;
                break;

            default:
                isincrease = 1;
                break;
        }

        createNormalJournalEntriesInputForTransfer(to_account_id, 2,isincrease);
    }

}




function createNormalJournalEntriesInputForTransfer(account_id, entry_id, isincrease)
{
    posting = $.post( base_url + "/accounting/account/get_account_details_ajax" , {
        // client_id: client_id,
        account_id: account_id,
        _method : "GET" } );
    posting.done(function( data ) {
        //Action/Response
        var description = element_id_value('memo');
        var amount = element_id_value('amount');
        var debit = ((data.iscredit_flag == 0 && isincrease == 1) || (data.iscredit_flag == 1 && isincrease == 0)) ? amount :0;
        var credit = ((data.iscredit_flag == 1 && isincrease == 1) || (data.iscredit_flag == 0 && isincrease == 0))  ? amount :0;

        entry_id = entry_id.toString();
        $('#account_id' + entry_id).val(account_id).change();
        $('#debit' + entry_id).val(debit).change();
        $('#credit' + entry_id).val(credit).change();
        $('#description' + entry_id).val(description).change();

    });
}
