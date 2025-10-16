@extends('supper-admin')
@php
    $page = 'User Profile';
    $meta_title = 'User Profile - BRJ';
    $meta_description = 'User Profile - BRJ';
    $mainPage = '';
    $mainPageLink = '';
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
    @include('supper-admin.inc.head')
    <div class="body">
        @include('components.breadcrumb')

        <div class="p-5 mt-5 bg-white shadow-lg">
            <div class="py-4">
                @if (session('success'))
                    <div
                        class="w-full py-2 flex justify-between items-center text-[13px] bg-green-100 text-green-700 px-2 mb-3 text-center badge">
                        <div>
                            {{ session('success') }}
                        </div>
                        <div class="cursor-pointer badgeClose">
                            <img width="15" src="{{ asset('media/icons/close-black.svg ') }}">
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="w-full py-2 flex justify-between items-center text-[13px] bg-red-100 text-red-700 px-2 mb-3 text-center badge">
                        <div>
                            {{ session('error') }}
                        </div>
                        <div class="cursor-pointer badgeClose">
                            <img width="15" src="{{ asset('media/icons/close-black.svg ') }}">
                        </div>
                    </div>
                @endif
                <form class="grid grid-cols-3 gap-4 w-full" method="post" action="">
                    @csrf
                    <div>
                        <label class="">
                            Full name:
                        </label>
                        <input type="text" name="name" required
                        {{-- value="{{ $user->name }}" --}}
                            class="w-full mt-2 px-3 py-2 border-b outline-none bg-secondary_2" placeholder="Full name">
                    </div>
                    <div>
                        <label class="">
                            Email address:
                        </label>
                        <input type="email" name="email" required
                        {{-- value="{{ $user->email }}" --}}
                            class="w-full mt-2 px-3 py-2 border-b outline-none bg-secondary_2" placeholder="Email Address">
                    </div>
                    <div>
                        <label class="">
                            Password:
                        </label>
                        <input type="text" name="password" value=""
                            class="w-full mt-2 px-3 py-2 border-b outline-none bg-secondary_2" placeholder="Password">
                    </div>
                    <input type="hidden" name="id"
                    {{-- value="{{ $user->id }}" --}}
                    >
                    <div class="w-full col-span-3 mt-3 text-right">
                        <button class="px-8 py-2 text-white bg-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
