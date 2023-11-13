<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Users') }}
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
                        data: 'identifier'
                        , name: 'identifier'
                    }
                    , {
                        data: 'name'
                        , name: 'name'
                    }
                    , {
                        data: 'username'
                        , name: 'username'
                    }
                    , {
                        data: 'role'
                        , name: 'role'
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
                <a href="{{route('user.create')}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                    + Register New Account
                </a>
            </div>
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIP</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
