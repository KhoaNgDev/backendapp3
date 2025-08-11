@extends('admin.layouts.master')
@section('module', 'Bảo trì định kỳ')
@section('action', 'Gửi nhăc bảo trì')

@section('admin-content')
    @include('admin.maintenance.partials.reminder-table')
@endsection

