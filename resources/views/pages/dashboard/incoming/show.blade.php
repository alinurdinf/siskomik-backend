<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {!! __('Incoming &raquo; Show') !!}
        </h2>
    </x-slot>

    <x-slot name="script">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                    <div class="mb-5" role="alert">
                        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                            There's something wrong!
                        </div>
                        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                            <p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </p>
                        </div>
                    </div>
                    @endif
                    <h2 class="font-semibold mb-5 text-xl leading-tight">
                        @role('sadmin|admin')
                        {!! __('Request Validation') !!}
                        @endrole
                        @role('mahasiswa')
                        {!! __('Request Result') !!}
                        @endrole
                    </h2>
                    <table class="table-auto w-full">
                        <tbody>
                            <tr>
                                <th class="border px-6 py-4 text-left">Ref Number</th>
                                <td class="border px-6 py-4">{{ $data->reference_number }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Subject</th>
                                <td class="border px-6 py-4">{{ $data->subject }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Type</th>
                                <td class="border px-6 py-4">
                                    <>{{ $data->type }}</< /td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Note</th>
                                <td class="border px-6 py-4">{{ $data->note}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Submit Date</th>
                                <td class="border px-6 py-4">{{ $data->submit_date}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Is Validated</th>
                                <td class="border px-6 py-4">{{ $data->is_validated ? 'VALIDATED' : 'UNVALIDATED'}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Received Document</th>
                                <td class="border px-6 py-4"><button data-modal-target="outgoing-file" data-modal-toggle="outgoing-file" class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline py-2" type="button">
                                        Show Document
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-left">Reply Document</th>
                                <td class="border px-6 py-4"><button data-modal-target="incoming-file" data-modal-toggle="incoming-file" class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline py-2" type="button">
                                        Show Document
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Main modal -->
                    <div id="outgoing-file" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Document Viewer
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="outgoing-file">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <iframe src="{{ route('incoming.show-pdf',$data->reference_number) }}" class="w-full h-screen"></iframe>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button data-modal-hide="outgoing-file" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                                    <button data-modal-hide="outgoing-file" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main modal -->
                    <div id="incoming-file" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Document Viewer
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="incoming-file">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <iframe src="{{ route('incoming.show-reply',$data->reference_number) }}" class="w-full h-screen"></iframe>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button data-modal-hide="incoming-file" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                                    <button data-modal-hide="incoming-file" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    @role('sadmin|admin')
                    <form action="{{route('incoming.validate')}}" method="POST">
                        @csrf
                        <input type="hidden" name="ref_number" value="{{$data->reference_number}}">
                        <div class="flex flex-wrap -mx-3 mb-6 mt-5">
                            <div class="w-full px-3 text-right">
                                <button id="dropdownHelperRadioButton" data-dropdown-toggle="dropdownHelperRadio" class="text-right text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button" required>
                                    Consent To <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="dropdownHelperRadio" class="mt-10 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-60 dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top" style="inset: auto auto 0px 0px; margin: 0px; transform: translate3d(522.5px, 6119.5px, 0px);" required>
                            <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHelperRadioButton">
                                <li>
                                    <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600" required>
                                        <div class="flex items-center h-5">
                                            <input id="radio-helper-4" name="approver" type="radio" value="DIREKTUR" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" required>
                                        </div>
                                        <div class="ml-2 text-sm">
                                            <label for="radio-helper-4" class="font-medium text-gray-900 dark:text-gray-300">
                                                <div>Direktur</div>
                                                <p id="radio-helper-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">Some helpful instruction goes over here.</p>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex p-2 rounded hover-bg-gray-100 dark-hover-bg-gray-600" required>
                                        <div class="flex items-center h-5">
                                            <input id="radio-helper-5" name="approver" type="radio" value="KPRODI" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark-bg-gray-600 dark-border-gray-500" required>
                                        </div>
                                        <div class="ml-2 text-sm">
                                            <label for="radio-helper-5" class="font-medium text-gray-900 dark-text-gray-300">
                                                <div>Kepala Prodi</div>
                                                <p id="radio-helper-text-5" class="text-xs font-normal text-gray-500 dark-text-gray-300">Some helpful instruction goes over here.</p>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="flex flex-wrap -mx-3 mb-6 mt-10">
                            <div class="w-full px-3 text-right">
                                @if(!$data->is_validated)
                                <button type="submit" class=" shadow-lg bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" required>
                                    Validasi
                                </button>
                                @else
                                <a href="#" class=" shadow-lg bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" required>
                                    Request Has Been Validated </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    @endrole

                    @role('direktur|kprodi')
                    <div class="flex flex-wrap -mx-3 mb-6 mt-10">
                        <div class="w-full px-3 text-left">
                            @if(!$data->is_validated)
                            <button data-modal-target="reply-modal" data-modal-toggle="reply-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                Reply
                            </button>
                            <!-- Main modal -->
                            <div id="reply-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-2xl max-h-full">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <!-- Modal header -->
                                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                Reply This Request, Ref Number {{$data->reference_number}}
                                            </h3>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="reply-modal">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <form action="{{route('incoming.reply')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="p-6 space-y-6">
                                                <div class="flex flex-wrap -mx-3 mb-6">
                                                    <input type="hidden" name="ref_number" value="{{$data->reference_number}}">
                                                    <input type="hidden" name="approver" value="{{auth()->user()->identifier}}">
                                                    <div class="w-full px-3">
                                                        <label class="block uppercase tracking-wide text-gray-700 dark:text-gray-300 text-xs font-bold mb-2" for="note">
                                                            Note
                                                        </label>
                                                        <textarea name="note" class="form-textarea w-full dark:bg-gray-200 dark:text-gray-700 text-gray-700 border border-gray-200 dark:border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="note" placeholder="Note">{{ old('note') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex flex-wrap -mx-3 mb-6">
                                                    <div class="w-full px-3">
                                                        <label class="block uppercase tracking-wide text-gray-700 dark:text-gray-300 text-xs font-bold mb-2" for="file">
                                                            File
                                                        </label>
                                                        <input name="file_path" type="file" class="w-full dark:bg-gray-200 dark:text-gray-700 text-gray-700 border border-gray-200 dark:border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="file"> </div>
                                                </div>
                                                <div class="flex flex-wrap -mx-3 mb-6 p-4 space-y-4">
                                                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-white">Do you agree with this letter?</h3>
                                                    <ul class="grid w-full gap-6 md:grid-cols-2">
                                                        <li>
                                                            <input type="radio" id="status-small" name="status" value="APPROVED" class="hidden peer" required>
                                                            <label for="status-small" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                                                <div class="block">
                                                                    <div class="w-full text-lg font-semibold">Approve</div>
                                                                    <div class="w-full">I Approve this latter</div>
                                                                </div>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" id="status-big" name="status" value="REJECTED" class="hidden peer">
                                                            <label for="status-big" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                                                <div class="block">
                                                                    <div class="w-full text-lg font-semibold">Reject</div>
                                                                    <div class="w-full">I Reject this letter</div>
                                                                </div>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send Reply</button>
                                                <button data-modal-hide="reply-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @else
                            <a href="#" class=" shadow-lg bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" required>
                                Request Has Been Reply </a>
                            @endif
                        </div>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
