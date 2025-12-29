<nav class="admin-navbar">
        <div class="admin-navbar-container">
                <div class="admin-navbar-left">
                        <button class="admin-toggle-btn" id="toggleSidebar">
                                <i class="fas fa-bars"></i>
                                <span>Menu</span>
                        </button>
                        <!-- Navbar Menu (Desktop Only) -->
                        <ul class="admin-navbar-menu">
                                <li class="admin-nav-item">
                                        <a class="admin-nav-link @if(route('admin.dashboard') == url()->current()) active @endif"
                                                href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-home"></i>
                                                <span>Dashboard</span>
                                        </a>
                                </li>
                                <li class="admin-nav-item">
                                        <a class="admin-nav-link @if(route('admin.order.list') == url()->current()) active @endif"
                                                href="{{ route('admin.order.list') }}">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>Orders</span>
                                        </a>
                                </li>
                                <li class="admin-nav-item">
                                        <a class="admin-nav-link @if(route('admin.user.list') == url()->current()) active @endif"
                                                href="{{ route('admin.user.list') }}">
                                                <i class="fas fa-users"></i>
                                                <span>Users</span>
                                        </a>
                                </li>
                                
                                {{-- <li class="admin-nav-item dropdown">
                                        <a class="admin-nav-link @if(str_contains(url()->current(), 'catalog')) active @endif"
                                                href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-box"></i>
                                                <span>Catalog</span>
                                                <i class="fas fa-chevron-down submenu-arrow"></i>
                                        </a>
                                        <ul class="dropdown-menu admin-navbar-dropdown">
                                                <li>
                                                        <a class="dropdown-item admin-dropdown-item"
                                                                href="{{ route('admin.catalog.category.list') }}">
                                                                <i class="fas fa-folder"></i>
                                                                <span>Category</span>
                                                        </a>
                                                </li>
                                                <li>
                                                        <a class="dropdown-item admin-dropdown-item"
                                                                href="{{ route('admin.catalog.product.list') }}">
                                                                <i class="fas fa-cube"></i>
                                                                <span>Products</span>
                                                        </a>
                                                </li>
                                        </ul>
                                </li> --}}
                                <li class="admin-nav-item dropdown">
                                        <a class="admin-nav-link @if(str_contains(url()->current(), 'brand')) active @endif"
                                                href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-tags"></i>
                                                <span>Brand</span>
                                                <i class="fas fa-chevron-down submenu-arrow"></i>
                                        </a>
                                        <ul class="dropdown-menu admin-navbar-dropdown">
                                                <li>
                                                        <a class="dropdown-item admin-dropdown-item"
                                                                href="{{ route('admin.brand.category.list') }}">
                                                                <i class="fas fa-folder"></i>
                                                                <span>Category</span>
                                                        </a>
                                                </li>
                                                <li>
                                                        <a class="dropdown-item admin-dropdown-item"
                                                                href="{{ route('admin.brand.product.list') }}">
                                                                <i class="fas fa-cube"></i>
                                                                <span>Products</span>
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                                <li class="admin-nav-item">
                                        <a class="admin-nav-link @if(route('admin.settings.index') == url()->current()) active @endif"
                                                href="{{ route('admin.settings.index') }}">
                                                <i class="fas fa-cog"></i>
                                                <span>Settings</span>
                                        </a>
                                </li>
                        </ul>
                </div>
                <div class="admin-navbar-right">
                        <div class="admin-navbar-user dropdown">
                                <div class="admin-navbar-user-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-circle"></i>
                                        <span>Admin</span>
                                        <i class="fas fa-chevron-down"></i>
                                </div>
                                <ul class="dropdown-menu admin-user-dropdown">
                                        <li>
                                                <a class="dropdown-item admin-dropdown-item" href="{{ route('admin.get.profile') }}">
                                                        <i class="fas fa-user-circle"></i>
                                                        <span>My Profile</span>
                                                </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                                <a class="dropdown-item admin-dropdown-item admin-logout-item" href="{{ route('admin.logout') }}">
                                                        <i class="fas fa-sign-out-alt"></i>
                                                        <span>Logout</span>
                                                </a>
                                        </li>
                                </ul>
                        </div>
                </div>
        </div>
</nav>

<!-- Sidebar -->
<div class="admin sidebar d-flex flex-column" id="sidebar">
        <!-- Sidebar Navigation -->
        <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                        <a class="nav-link @if(route('admin.dashboard') == url()->current()) active @endif"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                        </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link @if(route('admin.order.list') == url()->current()) active @endif"
                                href="{{ route('admin.order.list') }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Orders</span>
                        </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link @if(route('admin.user.list') == url()->current()) active @endif"
                                href="{{ route('admin.user.list') }}">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                        </a>
                </li>
               
                {{-- <li class="nav-item">
                        <a class="nav-link @if(str_contains(url()->current(), 'catalog')) active @endif"
                                href="#catalogSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="fas fa-box"></i>
                                <span>Catalog</span>
                                <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>
                        <div class="collapse @if(str_contains(url()->current(), 'catalog')) show @endif" id="catalogSubmenu">
                                <ul class="nav flex-column submenu">
                                        <li class="nav-item">
                                                <a class="nav-link submenu-link"
                                                        href="{{ route('admin.catalog.category.list') }}">
                                                        <i class="fas fa-folder"></i>
                                                        <span>Category</span>
                                                </a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link submenu-link"
                                                        href="{{ route('admin.catalog.product.list') }}">
                                                        <i class="fas fa-cube"></i>
                                                        <span>Products</span>
                                                </a>
                                        </li>
                                </ul>
                        </div>
                </li> --}}
                <li class="nav-item">
                        <a class="nav-link @if(str_contains(url()->current(), 'brand')) active @endif"
                                href="#brandSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="fas fa-tags"></i>
                                <span>Brand</span>
                                <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>
                        <div class="collapse @if(str_contains(url()->current(), 'brand')) show @endif" id="brandSubmenu">
                                <ul class="nav flex-column submenu">
                                        <li class="nav-item">
                                                <a class="nav-link submenu-link"
                                                        href="{{ route('admin.brand.category.list') }}">
                                                        <i class="fas fa-folder"></i>
                                                        <span>Category</span>
                                                </a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link submenu-link"
                                                        href="{{ route('admin.brand.product.list') }}">
                                                        <i class="fas fa-cube"></i>
                                                        <span>Products</span>
                                                </a>
                                        </li>
                                </ul>
                        </div>
                </li>
                <li class="nav-item">
                        <a class="nav-link @if(route('admin.settings.index') == url()->current()) active @endif"
                                href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                        </a>
                </li>
        </ul>
</div>

<!-- Overlay (for mobile) -->
<div class="admin overlay hide" id="overlay"></div>

<style>
        /* Admin Navbar Styling */
        .admin-navbar {
                background: #1e293b;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
                padding: 0;
                position: sticky;
                top: 0;
                z-index: 1030;
                border-bottom: 1px solid #334155;
        }

        .admin-navbar-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 24px;
                max-width: 100%;
                height: 64px;
        }

        .admin-navbar-left {
                display: flex;
                align-items: center;
                gap: 24px;
                flex: 1;
        }

        /* Navbar Menu (Desktop) */
        .admin-navbar-menu {
                display: none;
                list-style: none;
                margin: 0;
                padding: 0;
                align-items: center;
                gap: 8px;
                flex: 1;
        }

        .admin-nav-item {
                position: relative;
        }

        .admin-nav-item.dropdown {
                position: relative;
        }

        .admin-nav-link {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                color: #e2e8f0;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                border-radius: 4px;
                transition: all 0.2s ease;
                white-space: nowrap;
        }

        .admin-nav-link i {
                font-size: 14px;
        }

        /* Navbar submenu arrow - matches sidebar behavior */
        .admin-nav-link .submenu-arrow {
                font-size: 14px;
                transition: transform 0.2s ease, color 0.2s ease;
                margin-left: 8px;
                color: #cbd5e1;
        }

        .admin-nav-link:hover .submenu-arrow {
                color: #cbd5e1;
        }

        .admin-nav-link.active .submenu-arrow {
                color: #ffffff;
        }

        /* Rotate arrow when dropdown is shown - keep original color */
        .admin-nav-item.dropdown.show .admin-nav-link .submenu-arrow,
        .admin-nav-link[aria-expanded="true"] .submenu-arrow {
                transform: rotate(180deg) !important;
                /* Color remains unchanged - inherits from parent state */
        }

        .admin-nav-link:hover {
                background: #334155;
                color: #ffffff;
        }

        .admin-nav-link.active {
                background: #3b82f6;
                color: #ffffff;
        }

        .admin-nav-link.active i {
                color: #ffffff;
        }

        /* Navbar Dropdown Menu */
        .admin-navbar-dropdown {
                min-width: 200px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border-radius: 4px;
                padding: 4px 0;
                margin-top: 8px !important;
                background: #ffffff;
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 1000;
        }

        .admin-navbar-dropdown .admin-dropdown-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                border-radius: 0;
                color: #334155;
                font-size: 14px;
                font-weight: 500;
                transition: background-color 0.15s ease;
                text-decoration: none;
        }

        .admin-navbar-dropdown .admin-dropdown-item i {
                font-size: 16px;
                width: 18px;
                text-align: center;
                color: #64748b;
        }

        .admin-navbar-dropdown .admin-dropdown-item:hover {
                background: #f1f5f9;
                color: #1e293b;
        }

        .admin-navbar-dropdown .admin-dropdown-item:hover i {
                color: #3b82f6;
        }

        .admin-navbar-right {
                display: flex;
                align-items: center;
                gap: 16px;
        }

        /* Toggle Button Styling */
        .admin-toggle-btn {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border: 1px solid #334155;
                background: transparent;
                color: #e2e8f0;
                border-radius: 4px;
                font-weight: 500;
                font-size: 14px;
                transition: all 0.2s ease;
                cursor: pointer;
        }

        .admin-toggle-btn i {
                font-size: 16px;
        }

        .admin-toggle-btn:hover {
                background: #334155;
                border-color: #475569;
                color: #ffffff;
        }

        .admin-toggle-btn:active,
        .admin-toggle-btn:focus {
                background: #334155;
                outline: none;
                border-color: #475569;
        }

        /* Navbar Brand */
        .admin-navbar-brand {
                display: flex;
                align-items: center;
                gap: 12px;
                color: #ffffff;
                font-size: 16px;
                font-weight: 600;
                text-decoration: none;
                letter-spacing: -0.3px;
        }

        .admin-navbar-brand i {
                font-size: 20px;
                color: #3b82f6;
                background: transparent;
                padding: 0;
                border-radius: 0;
        }

        .admin-navbar-brand span {
                letter-spacing: 0;
        }

        /* Navbar User */
        .admin-navbar-user {
                position: relative;
        }

        .admin-navbar-user-toggle {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 6px 14px;
                background: transparent;
                border: 1px solid #334155;
                border-radius: 4px;
                color: #e2e8f0;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.2s ease;
                cursor: pointer;
        }

        .admin-navbar-user-toggle i:first-child {
                font-size: 18px;
                color: #64748b;
        }

        .admin-navbar-user-toggle i:last-child {
                font-size: 11px;
                margin-left: 6px;
                transition: transform 0.2s ease;
                color: #94a3b8;
        }

        .admin-navbar-user.show .admin-navbar-user-toggle {
                background: #334155;
                border-color: #475569;
        }

        .admin-navbar-user.show .admin-navbar-user-toggle i:last-child {
                transform: rotate(180deg);
        }

        .admin-navbar-user-toggle:hover {
                background: #334155;
                border-color: #475569;
                color: #ffffff;
        }

        .admin-navbar-user-toggle:hover i:first-child {
                color: #3b82f6;
        }

        /* User Dropdown Menu */
        .admin-user-dropdown {
                min-width: 200px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border-radius: 4px;
                padding: 4px 0;
                margin-top: 8px !important;
                background: #ffffff;
        }

        .admin-dropdown-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                border-radius: 0;
                color: #334155;
                font-size: 14px;
                font-weight: 500;
                transition: background-color 0.15s ease;
                text-decoration: none;
        }

        .admin-dropdown-item i {
                font-size: 16px;
                width: 18px;
                text-align: center;
                color: #64748b;
        }

        .admin-dropdown-item:hover {
                background: #f1f5f9;
                color: #1e293b;
        }

        .admin-dropdown-item:hover i {
                color: #3b82f6;
        }

        .admin-logout-item {
                color: #dc2626;
        }

        .admin-logout-item i {
                color: #dc2626;
        }

        .admin-logout-item:hover {
                background: #fef2f2;
                color: #dc2626;
        }

        .admin-logout-item:hover i {
                color: #dc2626;
        }

        .admin-user-dropdown .dropdown-divider {
                margin: 4px 0;
                border-color: #e2e8f0;
        }

        /* Sidebar Styling */
        .admin.sidebar {
                background: #f8fafc;
                box-shadow: 1px 0 3px rgba(0, 0, 0, 0.08);
                color: #334155;
                overflow-y: auto;
                overflow-x: hidden;
                border-right: 1px solid #e2e8f0;
        }

        /* Hide sidebar on large screens, show navbar menu */
        @media (min-width: 993px) {
                .admin.sidebar {
                        display: none !important;
                }

                .admin-navbar-menu {
                        display: flex !important;
                }

                .admin-toggle-btn {
                        display: none !important;
                }

                .admin.main-content {
                        margin-left: 0 !important;
                        width: 100% !important;
                }

                .admin.container {
                        display: block !important;
                }
        }


        .admin.sidebar::-webkit-scrollbar {
                width: 6px;
        }

        .admin.sidebar::-webkit-scrollbar-track {
                background: #f1f5f9;
        }

        .admin.sidebar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
        }

        .admin.sidebar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
        }

        /* Navigation Links */
        .sidebar-nav {
                padding: 12px 0;
        }

        .sidebar-nav .nav-item {
                margin-bottom: 8px;
        }

        .sidebar-nav .nav-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 20px;
                color: #475569;
                border-radius: 0;
                transition: background-color 0.15s ease;
                position: relative;
                font-weight: 500;
                font-size: 14px;
                border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link i {
                font-size: 16px;
                width: 20px;
                text-align: center;
                color: #64748b;
        }

        .sidebar-nav .nav-link span {
                flex: 1;
        }

        .sidebar-nav .nav-link:hover {
                background: #f1f5f9;
                color: #1e293b;
                border-left-color: #3b82f6;
        }

        .sidebar-nav .nav-link:hover i {
                color: #3b82f6;
        }

        .sidebar-nav .nav-link.active {
                background: #e0e7ff;
                color: #1e40af;
                border-left-color: #3b82f6;
                font-weight: 600;
        }

        .sidebar-nav .nav-link.active i {
                color: #3b82f6;
        }

        /* Submenu Styling */
        .submenu-arrow {
                font-size: 12px;
                transition: transform 0.2s ease;
                margin-left: auto;
                color: #64748b;
        }

        .nav-link[aria-expanded="true"] .submenu-arrow {
                transform: rotate(180deg);
                color: #3b82f6;
        }

        .submenu {
                background: #ffffff;
                padding: 4px 0;
                margin-top: 2px;
                border-left: 2px solid #e2e8f0;
        }

        .submenu-link {
                padding: 10px 20px 10px 48px !important;
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
        }

        .submenu-link i {
                font-size: 14px;
                color: #94a3b8;
        }

        .submenu-link:hover {
                background: #f1f5f9 !important;
                color: #1e293b !important;
        }

        .submenu-link:hover i {
                color: #3b82f6 !important;
        }


        /* Responsive adjustments */
        @media (max-width: 992px) {
                .admin.sidebar {
                        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.12);
                }

                .admin-navbar-menu {
                        display: none !important;
                }

                .admin-toggle-btn {
                        display: flex !important;
                }

                .admin-navbar-container {
                        padding: 0 16px;
                        height: 56px;
                }

                .admin-navbar-left {
                        gap: 16px;
                }

                .admin-navbar-brand span {
                        display: none;
                }

                .admin-navbar-brand i {
                        font-size: 18px;
                }

                .admin-toggle-btn span {
                        display: none;
                }

                .admin-toggle-btn {
                        padding: 6px 10px;
                }

                .admin-navbar-user-toggle span {
                        display: none;
                }

                .admin-navbar-user-toggle {
                        padding: 6px 10px;
                }

                .admin-user-dropdown {
                        right: 0 !important;
                        left: auto !important;
                }
        }

        @media (max-width: 480px) {
                .admin-navbar-container {
                        padding: 0 12px;
                        height: 52px;
                }

                .admin-navbar-left {
                        gap: 12px;
                }

                .admin-toggle-btn {
                        padding: 6px 8px;
                }

                .admin-navbar-user-toggle {
                        padding: 6px 8px;
                }

                .admin-user-dropdown {
                        right: 0 !important;
                        left: auto !important;
                        min-width: 180px;
                }
        }
</style>

<script>
        // Handle navbar dropdown arrow rotation - keep color unchanged
        document.addEventListener('DOMContentLoaded', function() {
                const dropdowns = document.querySelectorAll('.admin-nav-item.dropdown');
                
                dropdowns.forEach(function(dropdown) {
                        const dropdownToggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                        if (dropdownToggle) {
                                dropdown.addEventListener('show.bs.dropdown', function() {
                                        const arrow = dropdownToggle.querySelector('.submenu-arrow');
                                        if (arrow) {
                                                arrow.style.transform = 'rotate(180deg)';
                                                // Don't change color - keep original
                                        }
                                });
                                
                                dropdown.addEventListener('hide.bs.dropdown', function() {
                                        const arrow = dropdownToggle.querySelector('.submenu-arrow');
                                        if (arrow) {
                                                arrow.style.transform = 'rotate(0deg)';
                                                // Don't change color - keep original
                                        }
                                });
                        }
                });
        });
</script>