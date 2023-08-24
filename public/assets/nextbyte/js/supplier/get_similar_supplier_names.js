/*Setup*/

/*1. On text box for new buy price as helper put this code*/

/**
 *------Span container for alert------------
 *
 <span id="client_similar_names_label_div" style="display:none;">
 {!! small_helper(__('label.client.clients_with_similar_name')) !!}:  {!!  Form::label('', null, ['id' =>'client_similar_names_label']) !!} {!! alert_label(__('label.client.clients_with_similar_name_helper'), 'img/alert.gif') !!}
 </span>

 *---------------
 */


/*Get client with similar names when adding / editing supplier*/

function getSupplierWithSimilarNamesAlert(name, alert_label_id,alert_label_div, station_id = null, supplier_id = null,limit = null, offset = null){
    posting = $.post( base_url + "/operation/supplier/get_supplier_with_similar_name_ajax" , {
        // client_id: client_id,
        name: name,
        limit: limit,
        offset: offset,
        station_id: station_id,
        supplier_id : supplier_id,
        _method : "GET" } );
    posting.done(function( data ) {

        if(data.similar_supplier_names != null){
            hide_show('show_id',alert_label_div );
            $(alert_label_id).text(data.similar_supplier_names).change();
            $(alert_label_id).css("color", 'red');

        }else{
            hide_show('hide_id', alert_label_div);
            $(alert_label_id).text(null).change()

        }
    });
}

