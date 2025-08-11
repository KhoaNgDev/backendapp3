@extends('admin.layouts.master')

@section('module', 'Danh sách phản hồi sửa chữa')
@section('action', 'Danh sách')

@include('admin.feedbacks.partials._reply_modal')

@section('admin-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Danh sách phản hồi đánh giá</h4>
        </div>

        <div class="card-body">

            @include('admin.feedbacks.partials._filter_form')
            <div class="table-responsive">

                @include('admin.feedbacks.partials._table')

                {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
@endsection
@include('admin.feedbacks.partials._script')
