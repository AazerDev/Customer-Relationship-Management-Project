@extends('supper-admin')
@php
    $page = 'Users';
    $meta_title = 'Users - BRJ';
    $meta_description = 'Users - BRJ';
    $mainPage = '';
    $mainPageLink = '';
@endphp
@push('style')
@endpush

@section('content')
    @include('supper-admin.inc.head')
    <div class="body">
        @include('components.breadcrumb')
        <div class="relative p-4 mt-5 overflow-x-auto bg-white shadow-lg font-customFont">
            <div class="flex items-center justify-between pb-5">
                <h1 class="font-bold">Clients Details</h1>
                <div class="items-center space-x-2 flexv">
                    <x-button content="ADD NEW" iconBgColor="bg-primary" buttonIcon="plus-black" bgColor="bg-gray-300"
                        buttonAttr="" buttonLink="{{ route('users.create') }}" />
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 rtl:text-righ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Sr.</th>
                        <th scope="col" class="px-6 py-3">Client Name</th>
                        <th scope="col" class="px-6 py-3">Contact Number</th>
                        <th scope="col" class="px-6 py-3">Client Email</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Nortes Section</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">1</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">John Doe</td>
                        <td class="px-6 py-4">555-0123</td>
                        <td class="px-6 py-4">johndoe@example.com</td>
                        <td class="px-6 py-4">
                            <button class="p-2 text-white bg-red-500">Closed</button>
                        </td>
                        <td class="px-6 py-4">
                            <h3 class="px-2 ">Client is interested in scheduling a viewing for next week.</h3>
                        </td>
                        <td class="flex items-center px-6 py-4 space-x-3">
                            <a href="#" class="block p-2 transition-all cursor-pointer hover:bg-secondary_2"><img
                                    src="/media/icons/edit.svg" /></a>
                            <button class="p-2 transition-all cursor-pointer hover:bg-secondary_2 custom-model"
                                target-model="#delete">
                                <img src="/media/icons/Trash-red.svg" />
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">2</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Emily Davis</td>
                        <td class="px-6 py-4">555-0456</td>
                        <td class="px-6 py-4">emilydavis@example.com</td>
                        <td class="px-6 py-4">
                            <button class="p-2 text-white bg-green-500">Active</button>
                        </td>
                        <td class="px-6 py-4">
                            <h3 class="px-2">Waiting for client approval on the property offer.</h3>
                        </td>
                        <td class="flex items-center px-6 py-4 space-x-3">
                            <a href="#" class="block p-2 transition-all cursor-pointer hover:bg-secondary_2"><img
                                    src="/media/icons/edit.svg" /></a> <button
                                class="p-2 cursor-pointer hover:bg-secondary_2"><img
                                    src="/media/icons/Trash-red.svg" /></button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">3</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Michael Johnson</td>
                        <td class="px-6 py-4">555-0789</td>
                        <td class="px-6 py-4">michaeljohnson@example.com</td>
                        <td class="px-6 py-4">
                            <button class="p-2 text-white bg-green-500">Active</button>
                        </td>
                        <td class="px-6 py-4">
                            <button class="px-2">Follow-up needed; client did not respond after last contact.</button>
                        </td>
                        <td class="flex items-center px-6 py-4 space-x-3">
                            <a href="#" class="block p-2 transition-all cursor-pointer hover:bg-secondary_2"><img
                                    src="/media/icons/edit.svg" /></a> <button
                                class="p-2 cursor-pointer hover:bg-secondary_2"><img
                                    src="/media/icons/Trash-red.svg" /></button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <div id="delete" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Are You Sure?</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div class="col-span-2">
                    <h3 class="text-lg font-bold">Are you sure you want to delete this user?</h3>
                    <p class="mt-2 text-sm text-gray-500">This action cannot be undone.</p>
                </div>
            </div>
            <div class="popupFooter space-x-2">
                <button class="p-2 text-white bg-red-500">Delete</button>
                <button class="p-2 text-white bg-gray-500 modelClose">Cancel</button>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
