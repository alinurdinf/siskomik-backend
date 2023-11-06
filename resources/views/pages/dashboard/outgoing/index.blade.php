<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Outgoing') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            // AJAX DataTable
            var datatable = $('#crudTable').DataTable({
                ajax: {
                    url: '{!! url()->current() !!}'
                , }
                , columns: [{
                        data: 'id'
                        , name: 'id'
                        , width: '5%'
                    }
                    , {
                        data: 'reference_number'
                        , name: 'reference_number'
                    }
                    , {
                        data: 'subject'
                        , name: 'subject'
                    }
                    , {
                        data: 'from'
                        , name: 'from'
                    }
                    , {
                        data: 'to'
                        , name: 'to'
                    }
                    , {
                        data: 'type'
                        , name: 'type'
                    }
                    , {
                        data: 'submit_date'
                        , name: 'submit_date'
                    }
                    , {
                        data: 'status'
                        , name: 'status'
                    }
                    , {
                        data: 'action'
                        , name: 'action'
                        , orderable: false
                        , searchable: false
                        , width: '25%'
                    }
                , ]
            , });

        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{route('outgoing.create')}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                    + Add New Request
                </a>
            </div>

            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable" class="table-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ref Number</th>
                                <th>Subject</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Type</th>
                                <th>Submit Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
