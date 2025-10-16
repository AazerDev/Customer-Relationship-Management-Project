@extends('supper-admin')
@php
    $page = 'Reset Password';
    $meta_title = 'Reset Password - BRJ';
    $meta_description = 'Reset Password - BRJ';
@endphp


@push('style')
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
                        <div class="flex flex-col w-full pt-20">
                            <img class="w-[250px] mb-40" src="{{ asset('media/icons/logo-b.svg') }}" alt="">
                            <h2 class="text-2xl text-secondary">You have successfully changed <br>your password.</h2>
                            <a href="{{ route('home') }}"
                                class="flex justify-center items-center bg-primary w-full h-[50px] text-white text-lg mt-5">Login
                                Now</a>
                        </div>
                        <div class="text-gray-500 text-[12px] font-light md:mt-0 mt-4">
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
@endpush
