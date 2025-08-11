<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">Quản trị Admin</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">St</a>
        </div>
        <ul class="sidebar-menu">


            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i>
                    <span>Thống kê Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.dashboard.index') }}">Thống kê Dashboard</a></li>
                </ul>
            </li>


            <!-- Quản lý khách hàng -->
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i>
                    <span>Khách hàng</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.customers.index') }}">Danh sách khách hàng</a></li>
                </ul>
            </li>

            <!-- Quản lý sửa chữa -->
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i>
                    <span>Sửa chữa</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.repairs.index') }}">Danh sách sửa chữa</a></li>
                </ul>
            </li>

            <!-- Gợi ý bảo trì -->
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar-alt"></i>
                    <span>Bảo trì định kỳ</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.maintenance.reminders') }}">Gửi nhắc bảo trì</a></li>
                </ul>
            </li>

            <!-- Đánh giá khách hàng -->
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-star"></i>
                    <span>Đánh giá</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.repair.feedbacks.index') }}">Danh sách đánh giá</a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ route('admin.logouts') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Đăng xuất
            </a>
        </div>
    </aside>
</div>
