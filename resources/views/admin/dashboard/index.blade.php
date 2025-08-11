@extends('admin.layouts.master')

@section('module', 'Dashboard')
@section('action', 'Tổng quan')
@section('admin-content')

    {{-- Các thẻ thống kê --}}
    <div class="row g-3 mb-4" id="statCards"></div>

    {{-- Bảng lịch bảo trì --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-alt me-2"></i>Lịch bảo trì sắp tới</span>
                    <a href="{{ route('admin.maintenance.reminders') }}" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Ngày</th>
                                <th>Biển số</th>
                                <th>Chủ xe</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody id="maintenanceBody" class="text-center"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ thống kê --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">
                    <i class="fas fa-star text-warning me-1"></i>Đánh giá theo số sao
                </div>
                <div class="card-body">
                    <canvas id="ratingChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">
                    <i class="fas fa-chart-line text-info me-1"></i>Sửa chữa & Đăng ký xe theo tháng
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.dashboard.partials._script')
