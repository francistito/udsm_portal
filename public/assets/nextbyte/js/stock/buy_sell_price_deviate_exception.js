/*Setup*/

/*1. On text box for new buy price as helper put this code*/

/**
 *------Div container for alert/checkbox------------
 *
 <div class="checkbox-custom checkbox-default" id="acknowledge_buy_sell_dev_div" style="display:none">
 <input style="color:red;" type="checkbox" id="acknowledge_buy_sell_dev_id" name="acknowledge_buy_sell_dev_id">
 <label for="acknowledge_buy_sell_dev_id">{!! small_helper(trans('label.stock.offering.acknowledge_buy_sell_price_deviation'), 'red') !!}</label>
 </div>

 *---------------
 */

/*2. ON jquery script add action trigger and call checkValidationBuySellPriceDeviation() function with all its required parameters  which is triggered on key up of prices*/
/**
 *
 $('body').off('keydown', '.price_deviation').on('keyup', '.price_deviation', function(e) {
                checkValidationBuySellPriceDeviation();
            });


function checkValidationBuySellPriceDeviation()
{
    var whole_sell_price = element_id_value('whole_sell_price');
    var buy_price = element_id_value('buy_price');
    var deviation_percent = '{{  sysdef()->data('INVBYSLDEV') }}';
    validateBuySellPriceDeviation(parseFloat(removeThousandSeparator(buy_price)), parseFloat(removeThousandSeparator(whole_sell_price)), parseFloat(removeThousandSeparator(deviation_percent)), 'acknowledge_buy_sell_dev_id', 'acknowledge_buy_sell_dev_div', 'product')
}
*/

/*3. Script path:     {{ Html::script(url('assets/nextbyte/js/stock/buy_sell_price_deviate_exception.js')) }} */

function validateBuySellPriceDeviation(buy_price, sell_price,deviation_percent, acknowledge_check_id, acknowledge_check_div, submit_btn_id ){
    var actual_deviation = (1 - ( buy_price / sell_price)) * 100;
    actual_deviation = parseFloat(actual_deviation);
    if(actual_deviation >= 0){

        if(deviation_percent >= actual_deviation || actual_deviation == 100){
            /*Ok proceed - allowable*/
            hide_show('show_id', submit_btn_id);
            hide_show('hide_id', acknowledge_check_div);
            $('#'+acknowledge_check_id).prop('checked', false);
            $('#'+acknowledge_check_id).attr('disabled', true)
        }else{
            /*reset*/
            hide_show('hide_id', submit_btn_id);
            hide_show('show_id', acknowledge_check_div);
            $('#'+acknowledge_check_id).prop('checked', false);
            $('#'+acknowledge_check_id).attr('disabled', false)
        }
    }

}