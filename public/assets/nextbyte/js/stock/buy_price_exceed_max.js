/*Setup*/

/*1. On text box for new buy price as helper put this code*/

/**
 *------Span container for alert------------
 *
 <span id="{{ 'alert_buy_price' . $refill->id  }}"  style="display:none;">
 {!! small_helper(__('label.stock.product.recent_max_buy_price')) . ': ' . $refill->fuelTank->product->getRecentMaxBuyPriceAttribute() . ' ' . alert_label(__('label.stock.product.buy_price_exceed_max') , 'img/alert.gif')  !!}
 </span>

 *---------------
 */

/*2. ON jquery script add action trigger and call showAlertIfBuyPriceExceedRecentMaxBuyPrice() function with all its required parameters*/


function showAlertIfBuyPriceExceedRecentMaxBuyPrice(new_buy_price, recent_max_buy_price, alert_div_id){
    new_buy_price = removeThousandSeparator(new_buy_price);
    recent_max_buy_price = removeThousandSeparator(recent_max_buy_price);
    if(parseFloat(new_buy_price) > parseFloat(recent_max_buy_price)){
        hide_show('show_id',alert_div_id )
    }else{
        hide_show('hide_id',alert_div_id)
    }
}