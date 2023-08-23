@if(access()->customer()->customer_account_type_cv_id == 24)
<div class="row mt-2">
{{--    <h5 class="card-header"><b>@lang('label.custom_filter')</b></h5>--}}
    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-6 col-md-6 col-xs-12 col-xl-6">
                <div class="row">
                    <label class="col-lg-3 control-label text-lg-right pt-2">@lang('label.date_range')</label>
                    <div class="col-lg-9">
                        <div class="input-daterange input-group" data-plugin-datepicker="">
                            <span class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></span>
                            <input type="text" class="form-control datepicker" name="start_date" required autocomplete="off">
                            <span class="input-group-text border-left-0 border-right-0 rounded-0"> @lang('label.to_range') </span>
                            <input type="text" class="form-control datepicker" name="end_date" required autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-xl-3">
                <div class="row">
                    <label class="col-lg-4 control-label text-lg-right pt-2">@lang('label.status')</label>
                    <div class="col-lg-8">
                        <label>
                            <select class="form-control mb-3" name="status" required>
                                <option value=""></option>
                                <option value="1">@lang('label.payment.paid')</option>
                                <option value="0">@lang('label.payment.pending')</option>
                            </select>
                        </label>
                    </div>
                </div>

            </div>

            <div class="col-lg-3 col-md-3 col-xs-12 col-xl-3">
                <button class="btn btn-nextsms mx-auto" name="filter_button" id="btnFilter"><i class="fa fa-file-search"></i> @lang('label.filter')</button>
                <button class="btn btn-nextsms mx-auto" name="filter_reset" id="btnReset"><i class="fa fa-refresh"></i> @lang('label.reset')</button>


            </div>
        </div>
    </div>




</div>
@endif

<div class="row mt-3">
    <div class="col-sm-12" id="original_table">

        <table class="table table-hover table-responsive-lg table-lg" id="invoice-table" style="width:100%; border: 1px solid #bb9f9f;">
            <thead>
            <tr>
                @if(access()->customer()->customer_account_type_cv_id == 24)
                <th>@lang('label.sn')</th>
                <th>@lang('label.payment.invoice_number')</th>
                <th>@lang('label.payment.reference')</th>
                <th>@lang('label.status')</th>
                <th>@lang('label.payment.sms')</th>
                <th>@lang('label.payment.issued_on')</th>
                <th>@lang('label.payment.total_amount')</th>
                <th>@lang('label.actions')</th>
                    @else
                    <th>@lang('label.sn')</th>
                    <th>@lang('label.sms.title')</th>
                    <th>@lang('label.status')</th>
                    <th>@lang('label.payment.total_amount')</th>
                    <th>@lang('label.created_at')</th>
                    @endif
            </tr>
            </thead>
        </table>
    </div>

    <div class="col-sm-12 hidden" id="filter_table">

        <table class="table table-hover table-responsive-lg table-lg" id="filter_invoice_table" style="width:100%; border: 1px solid #bb9f9f;">
            <thead>
            <tr>
                <th>@lang('label.sn')</th>
                <th>@lang('label.payment.invoice_number')</th>
                <th>@lang('label.status')</th>
                <th>@lang('label.payment.sms')</th>
                <th>@lang('label.payment.issued_on')</th>
                <th>@lang('label.payment.total_amount')</th>
            </tr>
            </thead>
        </table>


    </div>
</div>


@push('after-scripts')

    {{ Html::script(url("assets/nextbyte/plugins/xdan/js/jquery.datetimepicker.full.min.js")) }}


    <script  type="text/javascript">

    $(document).ready( function () {

        let start_date = $("input[name='start_date']");
        let end_date = $("input[name='end_date']");
        let status = $("select[name='status']");

        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });
        // jQuery('.date').datetimepicker({
        //     // timepicker:true,
        //     format:'yy-mm-dd',
        // });
        // jQuery('.datepicker').datetimepicker({
        //     timepicker:false,
        //     format:'Y-m-d',
        //     weeks: true,
        //     dayOfWeekStart: 1,
        //     lazyInit: true,
        //     scrollInput: true
        // });

        function drawDatatable(from_date = '', end_date = '', status = '')
        {
            $('#filter_invoice_table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                stateSave: true,
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
                },
                dom: 'Bfrtip',
                buttons: [
                //     'copyHtml5',
                    'excelHtml5',
                //     'csvHtml5',
                //     'pdfHtml5',
                //     'print'
                ],
                ajax: {
                    url: "{{ route('payment.get_filtered_invoices_for_datatable') }}",
                    data: {
                        start_date: from_date,
                        end_date: end_date,
                        is_paid: status,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'number', name: 'number', orderable: true, searchable: true},
                    { data: 'status', name: 'status', orderable: true, searchable: true},
                    { data: 'recharge_quantity', name: 'recharge_quantity', orderable: true, searchable: true},
                    { data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                    { data: 'amount', name: 'amount', orderable: true, searchable: false},
                ]
            });
        }

        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: true,
            paging: true,
            info:true,
            stateSaveCallback: function (settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            {{--ajax: '{!! route('packages.invoice.get_invoice') !!}',--}}

            type: 'get',
                    @if(access()->customer()->customer_account_type_cv_id == 24)

            ajax: '{!! route('payment.get_invoices_for_datatable') !!}',

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                { data: 'number', name: 'number', orderable: true, searchable: true},
                { data: 'payment_reference', name: 'payment_reference', orderable: true, searchable: true},
                { data: 'status', name: 'status', orderable: true, searchable: true},
                { data: 'recharge_quantity', name: 'recharge_quantity', orderable: true, searchable: true},
                { data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                { data: 'amount', name: 'amount', orderable: true, searchable: false},
                { data: 'actions', name: 'actions', orderable: true, searchable: false},

            ],
            @else
            ajax: '{!! route('reseller.sub_customer_transfer', [access()->customer()->uuid, access()->customer()->parent_id]) !!}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                {data: 'smscount', name: 'smscount', orderable: true, searchable: true, width: '20%'},
                {data: 'status', name: 'status', orderable: true, searchable: true, width: '10%'},
                {data: 'amount', name: 'amount', orderable: true, searchable: true,  width: '25%'},
                {data: 'created_at', name: 'created_at', orderable: false, searchable: true,  width: '40%'},
            ],
            @endif

            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).click(function() {
                    document.location.href = url + "/payment/recharge/invoice/"    + aData['uuid'];
                }).hover(function() {
                    $(this).css('cursor','pointer');
                }, function() {
                    $(this).css('cursor','auto');
                });
            }
        });

        $("#btnFilter").click(function () {
            if  (start_date.val() !== '' && end_date.val() !== ''){
                $("#original_table").addClass('hidden');
                $("#filter_table").removeClass('hidden');
                $('#filter_invoice_table').DataTable().destroy();
                drawDatatable(start_date.val(), end_date.val(), status.val());
            }else{
                alert('Please select all provided fields for date range.')
            }
        });

        $("#btnReset").click(function () {
                $("#filter_table").addClass('hidden');
                $("#original_table").removeClass('hidden');
                $('#invoice-table').DataTable().destroy();
                $("input[name='start_date']").val('');
                $("input[name='end_date']").val('');
                $("select[name='status']").val('');
                $('#invoice-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: true,
                paging: true,
                info:true,
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
                },
                {{--ajax: '{!! route('packages.invoice.get_invoice') !!}',--}}
                ajax: '{!! route('payment.get_invoices_for_datatable') !!}',

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'number', name: 'number', orderable: true, searchable: true},
                    { data: 'payment_reference', name: 'payment_reference', orderable: true, searchable: true},
                    { data: 'status', name: 'status', orderable: true, searchable: true},
                    { data: 'recharge_quantity', name: 'recharge_quantity', orderable: true, searchable: true},
                    { data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                    { data: 'amount', name: 'amount', orderable: true, searchable: false},
                    { data: 'actions', name: 'actions', orderable: true, searchable: false},


                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).click(function() {
                        document.location.href = url + "/payment/recharge/invoice/"    + aData['uuid'];
                    }).hover(function() {
                        $(this).css('cursor','pointer');
                    }, function() {
                        $(this).css('cursor','auto');
                    });
                }
            });
        });
    });

</script>

@endpush
