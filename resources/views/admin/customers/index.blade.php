@extends('admin.layouts.master')
@section('module', 'Danh sách khách hàng')
@section('action', 'Danh sách')

@section('admin-content')
    <link rel="stylesheet" href="{{ asset('css/admin/customer.css') }}">

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                
                <div class="card-body">
                    @include('admin.customers.partials._filter_form')
                    @include('admin.customers.partials._datatable')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.customers.partials._datatable-script')
@endpush
