
<div class="row">
    <div class="col-md-8">

    </div>

    <div class="col-md-4">
        <a href="{{route('cms.slider.create')}}" type="button" class="mb-1 mt-1 mr-1 btn btn-xs btn-primary pull-right">{{trans('label.cms.slider.new')}}</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-responsive-md" id="blog-table">
            <thead>
            <tr>
{{--                <th>@lang('label.sn')</th>--}}
                <th>{{ __('label.title') }}</th>
                <th>{{ __('label.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@push('after-scripts')
    <script type="text/javascript">
        var url = "{{ url("/") }}";
        $('#blog-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url : '{{ route('cms.slider.get.admin_data') }}',
                type : 'get'
            },
            columns: [
                // { data: 'DT_Row_Index', name: 'DT_Row_Index', orderable: false, searchable: false},
                { data: 'title', name: 'title', orderable: false, searchable: true },
                { data: 'status', name: 'status', orderable: false, searchable: false },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).click(function() {
                    document.location.href = url + "/cms/slider/edit/" + aData['uuid'] ;
                }).hover(function() {
                    $(this).css('cursor','pointer');
                }, function() {
                    $(this).css('cursor','auto');
                });
            }
        });
    </script>
@endpush
