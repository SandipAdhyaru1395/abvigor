<nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
                <button class="btn btn-outline-primary" id="toggleSidebar">☰ Menu</button>
                {{-- <span class="navbar-brand mb-0 h1">Responsive Layout</span> --}}
        </div>
</nav>

<!-- Sidebar -->
<div class="admin sidebar d-flex flex-column p-3 border-end" id="sidebar">
        <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link @if(route('admin.dashboard') == url()->current()) active @endif"
                                aria-current="active" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a
                                class="nav-link @if(route('admin.order.list') == url()->current()) active @endif"
                                href="{{ route('admin.order.list') }}">Orders</a></li>
                <li class="nav-item"><a class="nav-link @if(route('admin.user.list') == url()->current()) active @endif"
                                href="{{ route('admin.user.list') }}">Users</a></li>
                <li class="nav-item"><a class="nav-link @if(str_contains(url()->current(), 'catalog')) active @endif"
                                href="#catalogSubmenu" data-bs-toggle="collapse">Catalog</a>
                        <div class="collapse ps-3" id="catalogSubmenu">
                                <ul class="nav flex-column">
                                        <li class="nav-item">
                                                <a class="nav-link"
                                                        href="{{ route('admin.catalog.category.list') }}">Category</a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link"
                                                        href="{{ route('admin.catalog.product.list') }}">Products</a>
                                        </li>
                                </ul>
                        </div>
                </li>
                <li class="nav-item">
                        <a class="nav-link @if(str_contains(url()->current(), 'brand')) active @endif"
                                href="#brandSubmenu" data-bs-toggle="collapse">Brand</a>
                        <div class="collapse ps-3" id="brandSubmenu">
                                <ul class="nav flex-column">
                                        <li class="nav-item">
                                                <a class="nav-link"
                                                        href="{{ route('admin.brand.category.list') }}">Category</a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link"
                                                        href="{{ route('admin.brand.product.list') }}">Products</a>
                                        </li>
                                </ul>
                        </div>
                </li>

                <li class="nav-item"><a class="nav-link @if(route('admin.get.profile') == url()->current()) active @endif"
                                href="{{ route('admin.get.profile') }}">My Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.logout') }}">Logout</a></li>
        </ul>
</div>

<!-- Overlay (for mobile) -->
<div class="admin overlay hide" id="overlay"></div>