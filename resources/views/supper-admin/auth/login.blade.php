@extends('supper-admin')
@php
    $page = 'Log In';
    $meta_title = 'Log In - BRJ';
    $meta_description = 'Log In - BRJ';
@endphp


@push('style')
    <style>
        .role {
            background: #CFE9FF;
            color: #9E9E9E;
        }

        .role.active {
            background: #3387CC;
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
                            <h4 class="mb-3 text-5xl font-light leading-snug text-mid-night-blue lg:mb-10">SBS - Auth Ready
                                KIT </h4>
                            <p class="text-[#2B6CEC]">lorem ipsum dummy text — <br> <span> anytime,
                                    anywhere!</span></p>
                            <img src="{{ asset('media/images/login.png') }}" width="100%" alt="">
                        </div>

                    </div>
                    <div class="lg:w-[60%] flex flex-col justify-between items-center m-auto h-full">
                        <div class="flex flex-col w-full pt-20 space-y-16">
                            <img class="w-[250px]" src="{{ asset('media/icons/logo-b.svg') }}" alt="">
                            <div>
                                <h2 class="text-secondary">Login as</h2>
                                <div class="flex my-3 space-x-4 font-light">
                                    <button class="w-[170px] h-[50px] cursor-pointer role active">Admin</button>
                                    <button class="w-[170px] h-[50px] cursor-pointer role">User</button>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('loggedin') }}" method="post" class="space-y-5">
                                    @csrf
                                    <div class="relative flex h-12 border border-gray-500">
                                        <input required autocomplete="off" name="email" id="email" type="text"
                                            class="w-full px-4 text-base bg-transparent bg-white outline-none peer" />
                                        <label
                                            class="absolute top-1/2 translate-y-[-50%] bg-white left-4 px-2 peer-focus:top-0 peer-focus:left-3 font-light text-gray-500 text-base peer-focus:text-sm peer-focus:text-primary peer-valid:-top-0 peer-valid:left-3 peer-valid:text-sm peer-valid:text-primary duration-150"
                                            for="email">
                                            Email Address
                                        </label>
                                    </div>
                                    <div class="relative flex h-12 border border-gray-500">
                                        <input required autocomplete="off" name="password" id="password" type="password"
                                            class="w-full px-4 text-base bg-transparent bg-white outline-none peer" />
                                        <label
                                            class="absolute top-1/2 translate-y-[-50%] bg-white left-4 px-2 peer-focus:top-0 peer-focus:left-3 font-light text-gray-500 text-base peer-focus:text-sm peer-focus:text-primary peer-valid:-top-0 peer-valid:left-3 peer-valid:text-sm peer-valid:text-primary duration-150"
                                            for="password">
                                            Password
                                        </label>
                                        <button id="password-visible" type="button" class="px-2 m-auto cursor-pointer">
                                            <img id="eye-icon" src="{{ asset('media/icons/eye-off.svg') }}"
                                                alt="Toggle Password Visibility">
                                        </button>
                                    </div>

                                    @if ($errors->has('email'))
                                        <div class="text-red-500 text-[12px] mt-2">{{ $errors->first('email') }}</div>
                                    @endif
                                    <div class="flex justify-end py-3 text-sm text-primary">
                                        <a href="{{ route('forgotpassword') }}" class="hover:underline w-fit">forgot
                                            Password?</a>
                                    </div>

                                    <button type="submit"
                                        class="bg-primary w-full h-[50px] text-white text-lg flex justify-center items-center">
                                        {{-- <div class="w-5 h-5 border-2 border-white rounded-full animate-spin border-l-transparent"></div> --}}
                                        Login
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="text-gray-500 text-[12px] font-light w-full md:mt-0 mt-4">
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
        $(document).ready(function() {
            $('.role').click(function() {
                $('.role').removeClass('active');
                $(this).addClass('active');
            });
            if ($('.role.active').length === 0) {
                $('.role:first').addClass('active');
            }

            $('#password-visible').click(function() {
                const input = $('#password');
                const eyeIcon = $('#eye-icon');

                // Toggle the input type between 'password' and 'text'
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text'); // Change to text to show the password
                    eyeIcon.attr('src',
                        '{{ asset('media/icons/eye-open.svg') }}'); // Change the icon to open eye
                } else {
                    input.attr('type', 'password'); // Change to password to hide it
                    eyeIcon.attr('src',
                        '{{ asset('media/icons/eye-off.svg') }}'); // Change the icon to closed eye
                }
            });
        });
    </script>
@endpush
