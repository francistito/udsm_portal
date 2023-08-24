/*Setup*/

/*1. On text box for entry put this code*/

/**
 *------Span container for alert------------
 *

 <span id="entry_similar_names_label_div" style="display:none;">
 {!! small_helper(__('label.entries_similar_name')) !!}:  {!!  Form::label('', null, ['id' =>'entry_similar_names_label']) !!} {!! alert_label(__('label.entries_similar_name_helper'), 'img/alert.gif') !!}
 </span>

 *---------------
 */


/*2. Js path*/

//    {{ Html::script(url('assets/nextbyte/js/general/get_similar_entry_names.js')) }}


/*3. Trigger to check for names*/

//-----start---------
/*
$('body').off('keydown', '#name').on('keyup', '#name', function(e) {
    var element_id = this.id;
    var name = element_id_value(element_id);
    var opt_where_raw = null;

    if(name.length > 1){
        getEntriesWithSimilarNamesAlert(table_name, name,'#entry_similar_names_label', 'entry_similar_names_label_div',col_name, opt_where_raw )
    }

});
*/

//-----ent=----------




/*Get entries with similar names when adding / editing client*/
    function getEntriesWithSimilarNamesAlert(table_name, name, alert_label_id,alert_label_div,column_name = 'name', opt_where_raw = null,limit = null, offset = null){
        posting = $.post( base_url + "/admin/system/get_entries_with_similar_names_ajax" , {
            // client_id: client_id,
            table_name: table_name,
            name: name,
            column_name: column_name,
            opt_where_raw: opt_where_raw,
            limit: limit,
            offset: offset,
        _method : "GET" } );
    posting.done(function( data ) {
        if(data.similar_entries_names != null){
            hide_show('show_id',alert_label_div );
            $(alert_label_id).text(data.similar_entries_names).change();
            $(alert_label_id).css("color", 'red');

        }else{
            hide_show('hide_id', alert_label_div);
            $(alert_label_id).text(null).change()

        }
    });
}

