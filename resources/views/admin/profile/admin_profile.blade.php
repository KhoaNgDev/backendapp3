@extends('admin.layouts.master')
@section('module', 'Admin')
@section('action', 'Hồ sơ cá nhân')

@section('admin-content')

    <h2 class="section-title">Xin chào, {{ Auth::user()->name }}!</h2>
    <p class="section-lead">
        Bạn có thể thay đổi thông tin cá nhân tại đây.
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
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    <img alt="image"
                        src="{{ !empty($data->photo) ? url('uploads/admin_images/' . $data->photo) : url('upload/no_image.jpg') }}"
                        class="rounded-circle profile-widget-picture">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Web Design</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">SEO</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Marketing</div>
                        </div>
                    </div>
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name">
                        {{ Auth::user()->name }}
                        <div class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div> Web Developer
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form action="{{ route('admin.profile.store') }}" method="post" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>Chỉnh sửa hồ sơ</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Tên người dùng</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $data->name) }}" required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập tên người dùng.
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-7 col-12">
                                <label>Email</label>
                                <input type="email" name="email" readonly
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $data->email) }}" required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập email hợp lệ.
                                </div>
                            </div>

                            <div class="form-group col-md-5 col-12">
                                <label>Số điện thoại</label>
                                <input type="tel" name="phone" placeholder="Nhập số điện thoại..."
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $data->phone) }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" onpaste="return false"
                                    onkeydown="return event.key !== '-'">

                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                <label>Ảnh đại diện</label>
                                <input class="form-control @error('photo') is-invalid @enderror" name="photo"
                                    type="file" id="image">
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-0 col-12">
                                <label>Ảnh hiện tại</label><br>
                                <div id="previewContainer">
                                    <img id="showImage"
                                        src="{{ !empty($data->photo) ? asset('uploads/admin_images/' . $data->photo) : asset('uploads/no_image.jpg') }}"
                                        alt="Admin" class=" p-1 bg-primary" width="150">
                                    <br>
                                    <button type="button" id="removeImageBtn" class="btn btn-sm btn-danger mt-2 d-none">
                                        Xoá ảnh vừa chọn
                                    </button>
                                </div>

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

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#image').change(function(e) {
                    const reader = new FileReader();
                    const file = e.target.files[0];

                    if (file) {
                        reader.onload = function(e) {
                            $('#showImage').attr('src', e.target.result);
                            $('#removeImageBtn').removeClass('d-none');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $('#removeImageBtn').click(function() {
                    $('#image').val('');
                    $('#showImage').attr('src',
                        '{{ !empty($data->photo) ? asset('uploads/admin_images/' . $data->photo) : asset('uploads/no_image.jpg') }}'
                    );
                    $(this).addClass('d-none');
                });

                document.querySelector('input[name="photo"]').addEventListener('change', function(e) {
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    const file = e.target.files[0];
                    if (file && !allowedTypes.includes(file.type)) {
                        alert('Chỉ chấp nhận file jpg, jpeg hoặc png.');
                        e.target.value = '';
                    }
                });
            });
        </script>
    @endpush


@endsection
