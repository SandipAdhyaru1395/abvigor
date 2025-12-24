<!-- Hidden Checkbox for CSS-only toggle -->
<input type="checkbox" id="sidebar-toggle" class="sidebar-toggle-checkbox">

<!-- Toggle Button (Outside Sidebar) -->
<label for="sidebar-toggle" class="front-sidebar-toggle" aria-label="Toggle Sidebar">
    <i class="fa fa-bars toggle-icon-bars"></i>
    <i class="fa fa-times toggle-icon-close"></i>
</label>

<!-- Sidebar -->
<div class="front sidebar d-flex flex-column" id="sidebar">
    <div class="sidebar-header">
        <h5><i class="fa fa-th-large"></i> Menu</h5>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link @if(route('get.dashboard') == url()->current()) active @endif" aria-current="active" href="{{ route('get.dashboard') }}">
                <i class="fa fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(route('order.list') == url()->current()) active @endif" href="{{ route('order.list') }}">
                <i class="fa fa-shopping-cart"></i> <span>Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(route('profile.get') == url()->current()) active @endif" href="{{ route('profile.get') }}">
                <i class="fa fa-user"></i> <span>My Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout.user') }}">
                <i class="fa fa-sign-out"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Overlay (for mobile) -->
<label for="sidebar-toggle" class="front overlay" id="overlay"></label>
