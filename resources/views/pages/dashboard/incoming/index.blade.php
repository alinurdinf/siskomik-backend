<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-grey-800 leading-tight">
            {{ __('Incoming') }}
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
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ref Number</th>
                                <th>Subject</th>
                                <th>From</th>
                                <th>To</th>
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
