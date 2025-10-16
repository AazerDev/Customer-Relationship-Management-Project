@extends('supper-admin')
@php
    $page = 'Create User';
    $meta_title = 'Create User - BRJ';
    $meta_description = 'Create User - BRJ';
    $mainPage = 'Users';
    $mainPageLink = route('user.list');
@endphp
@push('style')
@endpush

@section('content')
    @include('supper-admin.inc.head')
    <div class="body">
        @include('components.breadcrumb')
        <div class="py-4">
            <div class="bg-[#fff] py-4 w-full">
                <h2 class="font-poppins font-semibold text-lg text-[#032545] px-6">Add new user by selecting property preferences, and entering personal and contact details.</h2>
            </div>
            <form class="p-5 mt-5 bg-white">
                <div class="grid grid-cols-4 gap-5">
                    <!-- Client Name -->
                    <div class="w-full">
                        <label class="py-2 text-lg font-lexend text-secondary">First Name : <span class="text-red-600">
                                *</span></label>
                        <input class="w-full px-3 py-2 border-b outline-none bg-secondary_2" type="text"
                            placeholder="Enter First Name" />
                    </div>
                    <div class="w-full">
                        <label class="py-2 text-lg font-lexend text-secondary">Last Name : <span class="text-red-600">
                                *</span></label>
                        <input class="w-full px-3 py-2 border-b outline-none bg-secondary_2" type="text"
                            placeholder="Enter Last Name" />
                    </div>

                    <!-- Contatct -->
                    <div class="w-full">
                        <label class="py-2 text-lg font-lexend text-secondary">Conatct Number<span class="text-red-600">
                                *</span></label>
                        <input class="w-full px-3 py-2 border-b outline-none bg-secondary_2" type="text"
                            placeholder="Enter Conatct Number" />
                    </div>
                    <div class="w-full">
                        <label class="py-2 text-lg font-lexend text-secondary">Email<span class="text-red-600">
                                *</span></label>
                        <input class="w-full px-3 py-2 border-b outline-none bg-secondary_2" type="text"
                            placeholder="Enter Email" />
                    </div>
                    <!-- Submit Button -->
                    <div class="w-full col-span-4 mt-5 text-right">
                        <button class="px-8 py-2 text-white bg-primary">Create user   </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
