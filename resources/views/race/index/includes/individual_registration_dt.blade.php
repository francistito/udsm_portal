<div class="row" id="individual_registrations" style="display: none">
    <div class="col-md-12">
        <table class="table table-hover table-responsive-md" id="individual-table">
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Gender') }}</th>
                <th>{{ __('Address') }}</th>
                <th>{{ __('Race category') }}</th>
                <th>{{ __('Tshirt type') }}</th>
                <th>{{ __('Tshirt size') }}</th>
                <th>{{ __('Cost') }}</th>
                <th>{{ __('Status') }}</th>

            </tr>
            </thead>
        </table>
    </div>
</div>


@push('after-scripts')
    <script type="text/javascript">
        var url = "{{ url("/") }}";
        $('#individual-table').DataTable({

            processing: true,
            serverSide: true,
            ajax:{
                url : '{{ route('race.get_all_individual_for_dt') }}',
                data: function(d){

                    d.race_type =$('#type_input').val()

                },
                type : 'get'
            },
            columns: [
                { data: 'name', name: 'name', orderable: false, searchable: true },
                { data: 'phone_number', name: 'phone_number', orderable: false, searchable: false },
                { data: 'email', name: 'email', orderable: false, searchable: false },
                { data: 'gender', name: 'gender', orderable: false, searchable: true },
                { data: 'address', name: 'address', orderable: false, searchable: true },
                { data: 'category', name: 'category', orderable: false, searchable: true },
                { data: 'tshirt_type', name: 'tshirt_type', orderable: false, searchable: true },
                { data: 'tshirt_size', name: 'tshirt_size', orderable: false, searchable: true },
                { data: 'cost', name: 'cost', orderable: false, searchable: true },
                { data: 'status', name: 'status', orderable: false, searchable: true },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).click(function() {
                    document.location.href = url + "/cms/client/profile/" + aData['uuid'] ;
                }).hover(function() {
                    $(this).css('cursor','pointer');
                }, function() {
                    $(this).css('cursor','auto');
                });
            }
        });
    </script>
@endpush
