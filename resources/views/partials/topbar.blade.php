@php
  $isAdmin = request()->routeIs('admin.*');
  $authUser = $isAdmin ? auth('admin')->user() : auth('web')->user();
  $notifRoute = $isAdmin ? route('admin.notifications') : route('student.notifications');
  $profileRoute = $isAdmin ? route('admin.profile') : route('student.profile');
  $settingsRoute = $isAdmin ? route('admin.settings') : route('student.settings');
  $logoutRoute = $isAdmin ? route('admin.logout') : route('logout');
  $unreadCount = $authUser?->notifications()->unread()->count() ?? 0;
@endphp
<header class="dash-topbar">
  <button class="sidebar-toggle"><i class="fa-solid fa-bars"></i></button>
  <div class="dash-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Search {{ $isAdmin ? 'students, hostels, applications...' : '...' }}">
  </div>
  <div class="top-actions">
    <a href="{{ $notifRoute }}" class="icon-btn">
      <i class="fa-solid fa-bell"></i>
      @if($unreadCount)<span class="count">{{ $unreadCount }}</span>@endif
    </a>
    <div class="dropdown">
      <a href="{{ $profileRoute }}" class="topbar-user" data-bs-toggle="dropdown">
        <div class="avatar">{{ $authUser?->initials() }}</div>
        <div class="info d-none d-md-block">
          <span class="n">{{ $authUser?->name }}</span>
          <span class="r">{{ $isAdmin ? ($authUser?->role) : ($authUser?->matric_number) }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small text-muted d-none d-md-inline"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ $profileRoute }}"><i class="fa-solid fa-user me-2"></i>My Profile</a></li>
        <li><a class="dropdown-item" href="{{ $settingsRoute }}"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ $logoutRoute }}">
            @csrf
            <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>
