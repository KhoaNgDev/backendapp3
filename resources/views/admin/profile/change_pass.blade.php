@extends('admin.layouts.master')
@section('module', 'Admin')
@section('action', 'Đổi mật khẩu')

@section('admin-content')

    <h2 class="section-title">Xin chào, {{ Auth::user()->name }}!</h2>
    <p class="section-lead">
        Bạn có thể thay đổi mật khẩu cá nhân tại đây.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li style="list-style: none">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mt-sm-4">

        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <form action="{{ route('admin.update.password') }}" method="POST" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>Chỉnh sửa mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-12 col-12">
                                <label>Mật khẩu cũ</label>
                                <input type="password" name="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror" required placeholder="Nhập mật khẩu cũ tại đây.">
                                <div class="invalid-feedback">
                                    Vui lòng nhập mật khẩu cũ.
                                </div>
                                @error('old_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12 col-12">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror" required placeholder="Nhập mật khẩu mới tại đây.">
                                <div class="invalid-feedback">
                                    Vui lòng nhập mật khẩu mới.
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="form-group col-md-12 col-12">
                                <label>Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror" required placeholder="Nhập lại mật khẩu mới tại đây.">
                                <div class="invalid-feedback">
                                    Vui lòng xác nhận lại mật khẩu.
                                </div>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
