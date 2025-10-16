@extends('supper-admin')
@php
    $page = 'Reset Password';
    $meta_title = 'Reset Password - BRJ';
    $meta_description = 'Reset Password - BRJ';
@endphp


@push('style')
    <style>
        .fp.active {
            background: #D6D5D5;
            color: white;
        }

        .fp {
            background: #f3f3f3;
            color: #D6D5D5;
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
                        <div class="flex flex-col w-full pt-20 space-y-10">
                            <img class="w-[250px]" src="{{ asset('media/icons/logo-b.svg') }}" alt="">

                            <div>
                                <h2 class="text-2xl text-secondary">Forgot Password?</h2>
                                <p class="py-3 text-sm text-gray-500">Don't Worry! we will help you recover <br> your
                                    password
                                </p>
                            </div>

                            <div>
                                <form action="{{ route('createForgetPasswordToken') }}" method="post" autocomplete="off"
                                    class="space-y-5">
                                    @csrf
                                    <div class="relative flex h-12 border border-gray-500">
                                        <input required autocomplete="off" name="email" id="email"
                                            value="{{ old('email') }}" type="text"
                                            class="w-full px-4 text-base bg-transparent bg-white outline-none peer" />
                                        <label
                                            class="absolute top-1/2 translate-y-[-50%] bg-white left-4 px-2 peer-focus:top-0 peer-focus:left-3 font-light text-gray-500 text-base peer-focus:text-sm peer-focus:text-primary peer-valid:-top-0 peer-valid:left-3 peer-valid:text-sm peer-valid:text-primary duration-150"
                                            for="email">
                                            Email
                                        </label>
                                    </div>
                                    {{-- @if (session('error'))
                                <div class="text-red-500 text-[12px]">{{ session('error') }}</div>
                                @endif --}}

                                    <button type="submit"
                                        class="bg-primary w-full h-[50px] text-white text-lg flex justify-center items-center">
                                        Send </button>
                                </form>
                                <a href="{{ route('login') }}"> <button
                                        class="mt-3 bg-primary w-full h-[50px] text-white text-lg flex justify-center items-center">Back
                                        to Login </button></a>

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
            $('.fp').click(function() {
                $('.fp').removeClass('active');
                $(this).addClass('active');
            });
            if ($('.fp.active').length === 0) {
                $('.fp:first').addClass('active');
            }
        });
    </script>
@endpush
