<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Register New Account') }}
        </h2>
    </x-slot>

    <x-slot name="script">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                        <x-slot name="logo">
                            <x-authentication-card-logo />
                        </x-slot>
                
                        <x-validation-errors class="mb-4" />
                
                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf
                
                            <div>
                                <x-label for="identifier" value="{{ __('NIM or NIP') }}" />
                                <x-input id="identifier" class="block mt-1 w-full" type="text" name="identifier" :value="old('identifier')" required autofocus autocomplete="identifier" />
                            </div>
                
                            <div class="mt-4">
                                <x-label for="username" value="{{ __('Username') }}" />
                                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                            </div>
                
                            <div class="mt-4">
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            </div>
                
                            <div class="mt-4">
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            </div>
                
                            <div class="mt-4">
                                <x-label for="password" value="{{ __('Password') }}" />
                                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            </div>
                
                            <div class="mt-4">
                                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button id="dropdownHelperRadioButton" data-dropdown-toggle="dropdownHelperRadio" class="mt-4 text-gray-300 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-gray-800 dark:hover:bg-gray-900 dark:focus:ring-blue-800" type="button">Account Type<svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg></button>
                            </div>
                            <!-- Dropdown menu -->
                            <div id="dropdownHelperRadio" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-60 dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(522.5px, 6119.5px, 0px);">
                                <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHelperRadioButton">
                                    <li>
                                        <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <div class="flex items-center h-5">
                                                <input id="account-4" name="account" type="radio" value="MAHASISWA" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            </div>
                                            <div class="ml-2 text-sm">
                                                <label for="account-4" class="font-medium text-gray-900 dark:text-gray-300">
                                                    <div>Mahasiswa</div>
                                                    <p id="account-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">Some helpful instruction goes over here.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <div class="flex items-center h-5">
                                                <input id="account-5" name="account" type="radio" value="DOSEN" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            </div>
                                            <div class="ml-2 text-sm">
                                                <label for="account-5" class="font-medium text-gray-900 dark:text-gray-300">
                                                    <div>Dosen</div>
                                                    <p id="account-text-5" class="text-xs font-normal text-gray-500 dark:text-gray-300">Some helpful instruction goes over here.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            
                
                
                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <x-checkbox name="terms" id="terms" required />
                
                                        <div class="ms-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                            @endif
                
                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>
                
                                <x-button class="ms-4">
                                    {{ __('Register') }}
                                </x-button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
