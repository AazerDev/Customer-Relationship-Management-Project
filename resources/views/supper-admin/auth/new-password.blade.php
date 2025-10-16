@extends('supper-admin')
@php
    $page = 'Log In';
    $meta_title = 'Log In - BRJ';
    $meta_description = 'Log In - BRJ';
@endphp


@push('style')
    <style>
        .role {
            background: #bf854f3f;
            color: #9E9E9E;
        }

        .role.active {
            background: #BF864F;
            color: #fff;
        }
    </style>
@endpush

@section('content')

    <body class="bg-white">


        <div class="flex h-screen bg-white">
            <div class="m-auto lg:container">
                <div class="relative grid gap-6 px-5 md:grid-cols-2 lg:gap-10 lg:px-10">
                    <div class="relative hidden md:flex">
                        <div class="absolute bg-[#63FFEF] top-5 left-2 w-[60%] h-[230px] rounded-br-[150%]"></div>
                        <div class="px-10 pt-5 border border-white backdrop-blur-lg bg-white/40">
                            <h6 class="text-base font-bold text-mid-night-blue lg:text-lg lg:mb-10">Usage Tip</h6>
                            <h4 class="mb-3 text-5xl font-light leading-snug text-mid-night-blue lg:mb-10">SBS - Auth Ready KIT </h4>
                            <p class="text-[#2B6CEC]">lorem ipsum dummy text — <br> <span> anytime,
                                    anywhere!</span></p>
                            <img src="{{ asset('media/images/login.png') }}" width="100%" alt="">
                        </div>

                    </div>
                    <div class="lg:w-[60%] flex flex-col justify-between items-center m-auto h-full">
                        <div class="flex flex-col w-full pt-20 space-y-16">
                            {{-- <img class="w-[250px]" src="{{ asset('images/icons/logo-text.png') }}" alt=""> --}}
                            <h3 class=" text-5xl font-light leading-snug text-mid-night-blue">Set New Password</h3>
                            <div>
                                <form action="{{ route('update.password') }}" method="post" autocomplete="off"
                                    class="space-y-5">
                                    @csrf
                                    <div class="relative flex h-12 border border-gray-500">
                                        <input id="newPassword" required type="password" name="password" value=""
                                            placeholder="Enter New Password"
                                            class="w-full px-4 text-base bg-transparent bg-white outline-none peer" />
                                        <label
                                            class="absolute top-1/2 translate-y-[-50%] bg-white left-4 px-2 peer-focus:top-0 peer-focus:left-3 font-light text-gray-500 text-base peer-focus:text-sm peer-focus:text-primary peer-valid:-top-0 peer-valid:left-3 peer-valid:text-sm peer-valid:text-primary duration-150"
                                            for="newPassword">
                                            New Password
                                        </label>
                                    </div>

                                    <div class="relative flex h-12 border border-gray-500">
                                        <input id="confirmPassword" required type="password" name="confirmPassword"
                                            value="" placeholder="Confirm Password"
                                            class="w-full px-4 text-base bg-transparent bg-white outline-none peer" />
                                        <label
                                            class="absolute top-1/2 translate-y-[-50%] bg-white left-4 px-2 peer-focus:top-0 peer-focus:left-3 font-light text-gray-500 text-base peer-focus:text-sm peer-focus:text-primary peer-valid:-top-0 peer-valid:left-3 peer-valid:text-sm peer-valid:text-primary duration-150"
                                            for="confirmPassword">
                                            Confirm Password
                                        </label>
                                    </div>
                                    <div class="w-[350px]">
                                        <div id="strengthMessage" class="text-sm text-light-gray-2 mt-1 space-y-2">
                                            <div id="uppercaseText"
                                                class="text-[12px] flex items-center space-x-2 text-danger"> <img
                                                    src="{{ asset('images/icons/crossRed.svg') }}" alt="">
                                                <p> Add uppercase letters.</p>
                                            </div>
                                            <div id="numbersText"
                                                class="text-[12px] flex items-center space-x-2 text-danger"><img
                                                    id="numbersIcon" src="{{ asset('images/icons/crossRed.svg') }}"
                                                    alt="">
                                                <p> Add numbers.</p>
                                            </div>
                                            <div id="specialCharText"
                                                class="text-[12px] flex items-center space-x-2 text-danger"><img
                                                    id="specialCharIcon" src="{{ asset('images/icons/crossRed.svg') }}"
                                                    alt="">
                                                <p> Add special characters.</p>
                                            </div>
                                            <div id="lengthText"
                                                class="text-[12px] flex items-center space-x-2 text-danger"><img
                                                    id="lengthIcon" src="{{ asset('images/icons/crossRed.svg') }}"
                                                    alt="">
                                                <p>Password should be at least 8 characters long.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="emailCheck" value="{{ $email }}">
                                    <input type="hidden" name="tokenCheck" value="{{ $hash }}">
                                    <button type="submit"
                                        class="bg-primary w-full h-[50px] text-white text-lg flex justify-center items-center">
                                        Change </button>
                                </form>
                                <a href="{{ route('login') }}"> <button
                                        class="mt-3 bg-primary w-full h-[50px] text-white text-lg flex justify-center items-center">Back
                                        to Login </button></a>

                            </div>
                        </div>
                        <div class="text-gray-500 text-[12px] font-light mt-10">
                            {{-- <div class="flex items-center justify-between mb-4 space-x-7">
                            <p class="cursor-pointer hover:underline">Contact Support</p>
                            <p class="cursor-pointer hover:underline">Term of Use</p>
                            <p class="cursor-pointer hover:underline">Privacy Policy</p>
                        </div> --}}
                            <p class="italic">2025 BRJ. ALL RIGHTS RESERVED</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('keyup', function() {
            const newPasswordInput = document.getElementById('newPassword');
            const uppercaseText = document.getElementById('uppercaseText');
            const numbersText = document.getElementById('numbersText');
            const specialCharText = document.getElementById('specialCharText');
            const lengthText = document.getElementById('lengthText');

            function checkPasswords() {
                const newPassword = newPasswordInput.value;
                const {
                    hasUppercase,
                    hasNumber,
                    hasSpecialChar,
                    isLengthValid
                } = checkPasswordStrength(newPassword);
                updateIcon(uppercaseText, hasUppercase);
                updateIcon(numbersText, hasNumber);
                updateIcon(specialCharText, hasSpecialChar);
                updateIcon(lengthText, isLengthValid);
            }

            function checkPasswordStrength(password) {
                const minLength = 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecialChar = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(password);
                const isLengthValid = password.length >= minLength;

                return {
                    hasUppercase,
                    hasNumber,
                    hasSpecialChar,
                    isLengthValid
                };
            }

            function updateIcon(iconElement, condition) {
                const icon = iconElement.querySelector('img');
                const text = iconElement.querySelector('p');

                if (condition) {
                    icon.src = "{{ asset('images/icons/tickgreen.svg') }}";
                    iconElement.classList.remove('text-danger');
                    iconElement.classList.add('text-dark-green');
                } else {
                    icon.src = "{{ asset('media/icons/crossRed.svg') }}";
                    iconElement.classList.remove('text-dark-green');
                    iconElement.classList.add('text-danger');
                }
            }
            newPasswordInput.addEventListener('input', checkPasswords);
        });
    </script>
@endpush
