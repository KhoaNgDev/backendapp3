<nav class="navbar navbar-expand-lg main-navbar">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        
        </ul>
        <ul class="navbar-nav ml-auto navbar-right">
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image"
                        src="{{ Auth::user()->photo ? asset('uploads/admin_images/' . Auth::user()->photo) : asset('assets/img/avatar/avatar-1.png') }}"
                        class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">
                        Xin chào, {{ Auth::user()->name ?? 'Khách' }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i> Hồ sơ
                    </a>
                    <a href="{{ route('admin.password.change') }}" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i> Đổi mật khẩu
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.logouts') }}" class="dropdown-item has-icon text-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('admin.logouts') }}" method="get" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

</nav>

<script type="text/javascript">
    function filterRoutes() {
        const input = document.getElementById('route-search-input').value.toLowerCase();
        const items = document.querySelectorAll('#search-list .search-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? '' : 'none';
        });
    }
</script>
