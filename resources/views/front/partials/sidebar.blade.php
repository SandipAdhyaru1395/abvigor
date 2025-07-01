<nav class="navbar navbar-light bg-light d-md-none">
    <div class="container-fluid">
        <button class="btn btn-outline-primary" id="toggleSidebar">☰ Menu</button>
        {{-- <span class="navbar-brand mb-0 h1">Responsive Layout</span> --}}
    </div>
</nav>

<!-- Sidebar -->
<div class="front sidebar d-flex flex-column p-3 border-end" id="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link @if(route('get.dashboard') == url()->current()) active @endif" aria-current="active" href="{{ route('get.dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link @if(route('order.list') == url()->current()) active @endif" href="{{ route('order.list') }}">Orders</a></li>
        <li class="nav-item"><a class="nav-link @if(route('profile.get') == url()->current()) active @endif" href="{{ route('profile.get') }}">My Profile</a></li>
         <li class="nav-item"><a class="nav-link" href="{{ route('logout.user') }}">Logout</a></li>
    </ul>
</div>

<!-- Overlay (for mobile) -->
<div class="front overlay hide" id="overlay"></div>
