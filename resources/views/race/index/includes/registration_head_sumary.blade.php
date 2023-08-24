<div class="row">
    {{--        task summary--}}
    <div class="col-lg-12">
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">

                            <section class="card text-center">
                                <div class="card-body">
                                    <div class="widget-summary widget-summary-md stock_status" data-valuee="1" data-title="search">
                                        <div class="row">
                                            <div class="col" style="cursor: pointer">
                                                <div class="pull-left">
                                                    <div  id="individual" class="h2 font-weight-bold mb-0">
                                                        <input id="type_input" value="1" hidden>
                                                        <i class="fas fa-spinner spin_stock_status" id="spin_stock_status" style=''></i>
                                                    </div>
                                                    <p data-toggle="tooltip" data-placement="top" title="" class="text-2 text-muted mb-0" data-original-title="">{{trans('Individual')}}</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="col-md-6">
                            <a id="reset_summary" style="display: none;cursor: pointer" class="search" data-title="reset"><i class="fa fa-times fa-2x"></i></a>

                            <section class="card text-center">
                                <div class="card-body">
                                    <div class="widget-summary widget-summary-md stock_status" data-valuee="2" data-title="search">
                                        <div class="row">

                                            <div class="col">
                                                <div class="pull-left" style="cursor: pointer">
                                                    <div id="group"  class="h2 font-weight-bold mb-0">
                                                        <input id="type_input" value="2" hidden>

                                                        <i class="fas fa-spinner spin_stock_status" id="spin_stock_status" style=''></i>

                                                    </div>
                                                    <p data-toggle="tooltip" data-placement="top" title="" class="text-2 text-muted mb-0" data-original-title="">{{trans('Group')}}</p>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </section>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
