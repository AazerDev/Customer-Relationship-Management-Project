@extends('supper-admin')
@php
    $page = 'Index';
    $meta_title = 'Index - BRJ';
    $meta_description = 'Index - BRJ';
    $mainPage = '';
    $mainPageLink = '';
@endphp
@push('style')
@endpush
@section('content')
    @include('supper-admin.inc.head')
    <div class="body">
        @include('components.breadcrumb')
    </div>
@endsection
@push('script')
@endpush
