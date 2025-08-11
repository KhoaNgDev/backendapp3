<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layouts.partials.head')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('admin.layouts.partials.navbar')
            @include('admin.layouts.partials.main-sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @include('admin.layouts.partials.section-header')
                    <div class="section-body">
                        @yield('admin-content')
                    </div>
                </section>
            </div>

            @include('admin.layouts.partials.footer')
        </div>
        <form id="delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>


    @include('admin.layouts.partials.foot')
    @push('scripts')
        @if (session('success'))
            <script type="text/javascript">
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: '{{ session('error') }}'
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif
    @endpush
</body>

</html>
