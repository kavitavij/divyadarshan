<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon cil-speedometer"></i> Dashboard
                </a>
            </li>

            <li class="nav-title">Management</li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="nav-icon cil-calendar"></i> Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="nav-icon cil-user"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.edit') ? 'active' : '' }}"
                    href="{{ route('admin.settings.edit') }}">
                    <i class="nav-icon cil-file"></i> Manage Page Content
                </a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
