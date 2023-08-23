@extends('cms.layouts.main', ['title' => __('label.cms.client.client_lists') , 'header' => __('label.cms.client.client_lists')])

@include('includes.components.datatable_assets')

@push('after-styles')
    <style>
    </style>
@endpush

@section('content')
    <section class="card mb-4">
        {{--Start: Datatable--}}
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-responsive-md" id="client-table">
                        <thead>
                        <tr>
                            <th>{{ __('label.name') }}</th>
                            <th>{{ __('label.phone') }}</th>
                            <th>{{ __('label.email') }}</th>
                            <th>{{ __('label.region') }}</th>

                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script type="text/javascript">
        var url = "{{ url("/") }}";
        $('#client-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url : '{{ route('race.get_all_for_dt') }}',
                type : 'get'
            },
            columns: [
                { data: 'name', name: 'name', orderable: false, searchable: true },
                { data: 'tin', name: 'tin', orderable: false, searchable: false },
                { data: 'phone', name: 'phone', orderable: false, searchable: false },
                { data: 'email', name: 'email', orderable: false, searchable: false },
                { data: 'region', name: 'regions.name', orderable: false, searchable: true },
                // { data: 'contact_person', name: 'contact_person', orderable: false, searchable: false },
                // { data: 'contact_person_phone', name: 'contact_person_phone', orderable: false, searchable: false },

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
