<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {!! __('Outgoing &raquo; Create') !!}
        </h2>
    </x-slot>

    <x-slot name="script">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
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
                    <h2 class="font-semibold text-xl text-grey mb-5 leading-tight">
                        Request Letter
                    </h2>
                    <form class="w-full" action="{{route('outgoing.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 dark:text-gray-300 text-xs font-bold mb-2" for="subject">
                                    Subject
                                </label>
                                <input value="{{ old('subject') }}" name="subject" class="appearance-none block w-full dark:bg-gray-200 dark:text-gray-700 text-gray-700 border border-gray-200 dark:border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="subject" type="text" placeholder="Subject">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 dark:text-gray-300 text-xs font-bold mb-2" for="note">
                                    Letter Type
                                </label>
                                <select name="type" class="select2 w-full dark:bg-gray-200 dark:text-gray-700 text-gray-700 border border-gray-200 dark:border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="type">
                                    <option value="Surat Keterangan Mahasiswa Aktif" {{ old('type') == 'Surat Keterangan Mahasiswa Aktif' ? 'selected' : '' }}>Surat Keterangan Mahasiswa Aktif</option>
                                    <option value="Surat Keterangan Cuti" {{ old('type') == 'Surat Keterangan Cuti' ? 'selected' : '' }}>Surat Keterangan Cuti</option>
                                    <option value="Surat Keterangan Mengundurkan Diri" {{ old('type') == 'Surat Keterangan Mengundurkan Diri' ? 'selected' : '' }}>Surat Keterangan Mengundurkan Diri</option>
                                    <option value="Surat Keterangan Untuk Beasiswa" {{ old('type') == 'Surat Keterangan Untuk Beasiswa' ? 'selected' : '' }}>Surat Keterangan Untuk Beasiswa</option>
                                    <option value="Surat Pengantar Prakerin" {{ old('type') == 'Surat Pengantar Prakerin' ? 'selected' : '' }}>Surat Pengantar Prakerin</option>
                                    <option value="Surat Pengantar TA" {{ old('type') == 'Surat Pengantar TA' ? 'selected' : '' }}>Surat Pengantar TA</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3 mb-6">
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
                                <input name="file_path" type="file" class="w-full dark:bg-gray-200 dark:text-gray-700 text-gray-700 border border-gray-200 dark:border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="file">
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 text-right">
                                <button type="submit" class=" shadow-lg bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Send
                                </button>
                            </div>
                        </div>
                </div>
                </form>

            </div>
        </div>
    </div>
    </div>
</x-app-layout>
