@extends('admin.layouts.master')
@section('module', 'Danh sách sửa chữa')
@section('action', 'Danh sách')

@section('admin-content')

    @include('admin.repairs.partials.repair-table')

@endsection
