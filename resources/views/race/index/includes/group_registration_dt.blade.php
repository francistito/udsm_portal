<div class="row" id="group_registrations" style="display: none">
    <div class="col-md-12">
        <table class="table table-hover table-responsive-md" id="group-table">
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Address') }}</th>
                <th>{{ __('Runners ') }}</th>
                <th>{{ __('Tshirts') }}</th>
                <th>{{ __('Cost') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>


@push('after-scripts')
    <script type="text/javascript">
        var url = "{{ url("/") }}";
        $('#group-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url : '{{ route('race.get_all_group_for_dt') }}',
                data: function(d){
                    d.race_type =$('#type_input').val()
                },
                type : 'get'
            },
            columns: [
                { data: 'name', name: 'name', orderable: false, searchable: true },
                { data: 'phone_number', name: 'phone_number', orderable: false, searchable: false },
                { data: 'email', name: 'email', orderable: false, searchable: false },
                { data: 'address', name: 'address', orderable: false, searchable: true },
                { data: 'runners', name: 'runners', orderable: false, searchable: true },
                { data: 'tshirts', name: 'tshirts', orderable: false, searchable: true },
                { data: 'cost', name: 'cost', orderable: false, searchable: true },
                { data: 'status', name: 'status', orderable: false, searchable: true },
                { data : "actions", "className": "text-center",
                    render: function(data, type, row, meta){
                        return  `
                           <div class="btn-group flex-wrap pull-right">
                            ${(row.status == 0) ? '<a class="view_inventory" href="{{}}" id="view_inventory"><b>{{trans('Confirm')}}</b></a>' : '<a class="view_offering" href="{{route('race.change_status',0)}}" id="view_offering"><b>{{trans('Unconfirm')}}</b></a>'}   </div>`
                    }
                },
            ],

        });
    </script>
@endpush
