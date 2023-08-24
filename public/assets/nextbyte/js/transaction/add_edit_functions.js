/*action buttons options when loading content*/
function actionButtonsOptionGen(disallow_editing, element_class)
{
    if(disallow_editing){
        $('.'+element_class).hide();
    }else{
        $('.'+element_class).show();
    }
}

/*Trans count based on details*/
function transCountBadge(table_id, trans_label_count_id)
{
    var rowCount = $('#' +table_id + ' tr').length;
    if(rowCount > 0){
        $('#' + trans_label_count_id).text(rowCount).change();
        hide_show('show_id' , trans_label_count_id);
    }else{
        $('#' + trans_label_count_id).text(rowCount).change();
        hide_show('hide_id' , trans_label_count_id);
    }
}


/*Update total summary on save*/
function totalOverallSummary(total_summary_parsed)
{
    var sub_total_overall = total_summary_parsed.sub_total_overall;
    var tax_amount_overall = total_summary_parsed.tax_amount_overall;
    var total_amount_overall = total_summary_parsed.total_amount_overall;

    $('#sub_total_overall').text(accountingNumberPresentation(sub_total_overall)).change();
    $('#tax_amount_overall').text(accountingNumberPresentation(tax_amount_overall)).change();
    $('#total_amount_overall').text(accountingNumberPresentation(total_amount_overall)).change();
    /*Bind expense amount*/
    $('#total_amount_top').text('Amount: ' + accountingNumberPresentation(total_amount_overall)).change();
    $('#net_expense_amount').val(accountingNumberPresentation(total_amount_overall)).change();
    $('#total_bill_payable').val(accountingNumberPresentation(total_amount_overall)).change();
    /*for sale*/
    $('#total_sale_payable').val(accountingNumberPresentation(total_amount_overall)).change();
    $('#net_sale_amount').val(accountingNumberPresentation(total_amount_overall)).change();

}





/*Load project offering for select*/
function loadProjectOfferingsForSelect(project_id, element_id)
{
    if(project_id != null && project_id != ''){
        $.get( base_url + "/operation/project/get_offerings_select_ajax?project_id="+ project_id, function (data) {
            $("#spin2").show();
            $('#' + element_id).empty();
            $("#" + element_id).select2("val", "");
            $('#' + element_id).html(data);
            $("#spin2").hide();

        });
    }else{


        reloadOptionsForSelect(element_id, 'offerings_query', 'offering_name', 'offering_id')
    }
}

/*Load project clients for select*/
function loadProjectClientsForSelect(project_id, element_id)
{
    if(project_id != null && project_id != ''){
        $.get(base_url +"/operation/project/get_clients_select_ajax?project_id="+ project_id, function (data) {
            $("#spin2").show();
            $('#' + element_id).empty();
            $("#" + element_id).select2("val", "");
            $('#' + element_id).html(data);
            $("#spin2").hide();
        });
    }else{


        reloadOptionsForSelect(element_id, 'clients_query')
    }
}

/*Load options by station i.e. project, client, service*/
function loadOptionsByStationForSelect(station_id, parameter, element_id,custom_name  = 'name', custom_id = 'id', par_type = 'category_parameters', model_name = 'Expense', store_type_cv_id = null, isedit = 0, issubscription_service = 2)
{
    if(station_id != null && station_id != '' && store_type_cv_id != null){
        $.get(base_url + "/operation/transaction/get_options_by_station_select_ajax?station_id="+ station_id + '&parameter='+ parameter+ '&par_type='+ par_type+ '&model_name='+ model_name  + '&store_type_cv_id='+ store_type_cv_id + '&isedit='+ isedit+ '&issubscription_service='+ (issubscription_service) , function (data) {
            $("#spin2").show();
            $('#' + element_id).empty();
            $("#" + element_id).select2("val", "");
            $('#' + element_id).html(data);
            $("#spin2").hide();

        });
    }else{

            /*reload only for other parameters but not offerings*/
            reloadOptionsForSelect(element_id, parameter,custom_name,custom_id, par_type, model_name, store_type_cv_id, isedit);



    }
}

/*reload options as default*/
function reloadOptionsForSelect(element_id,parameter, custom_name  = 'name', custom_id = 'id',  par_type = 'category_parameters', model_name = 'Expense', isedit  = 0)
{

    if(parameter != null && parameter != ''){
        $.get( base_url + "/operation/transaction/reload_options_select_ajax?parameter="+ parameter +"&custom_name="+ custom_name +"&custom_id="+ custom_id + '&par_type='+ par_type+ '&model_name='+ model_name+ '&isedit='+ isedit  , function (data) {

            $('#' + element_id).html(data.parameter);
            $("#spin2").show();
            $('#' + element_id).empty();
            $("#" + element_id).select2("val", "");
            $('#' + element_id).html(data);
            $("#spin2").hide();

        });
    }
}
