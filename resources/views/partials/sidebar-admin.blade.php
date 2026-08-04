<aside class="dash-sidebar">
  <div class="side-brand">
    <img src="{{ asset('assets/images/sumas-logo.png') }}" alt="SUMAS Logo">
    <div class="brand-text"><span class="name">SUMAS Hostels</span><span class="role">Admin Console</span></div>
  </div>
  <nav class="side-nav">
    <div class="nav-section-label">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
    <div class="nav-section-label">Management</div>
    <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}"><i class="fa-solid fa-user-graduate"></i><span>Students</span></a>
    <a href="{{ route('admin.hostels') }}" class="nav-link {{ request()->routeIs('admin.hostels') ? 'active' : '' }}"><i class="fa-solid fa-building"></i><span>Hostel Management</span></a>
    <a href="{{ route('admin.messages') }}" class="nav-link {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
      <i class="fa-solid fa-envelope-open-text"></i><span>Messages</span>
      @php $msgUnread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
      @if($msgUnread) <span class="badge rounded-pill bg-danger">{{ $msgUnread }}</span> @endif
    </a>
    <a href="{{ route('admin.rooms') }}" class="nav-link {{ request()->routeIs('admin.rooms') ? 'active' : '' }}"><i class="fa-solid fa-door-closed"></i><span>Room Management</span></a>
    <a href="{{ route('admin.applications') }}" class="nav-link {{ request()->routeIs('admin.applications') ? 'active' : '' }}">
      <i class="fa-solid fa-file-lines"></i><span>Applications</span>
      @php $pendingCount = \App\Models\HostelApplication::pending()->count(); @endphp
      @if($pendingCount) <span class="badge rounded-pill bg-danger">{{ $pendingCount }}</span> @endif
    </a>
    <a href="{{ route('admin.allocation') }}" class="nav-link {{ request()->routeIs('admin.allocation') ? 'active' : '' }}"><i class="fa-solid fa-key"></i><span>Hostel Allocation</span></a>
    <a href="{{ route('admin.occupancy') }}" class="nav-link {{ request()->routeIs('admin.occupancy') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i><span>Occupancy Monitoring</span></a>
    <div class="nav-section-label">Insights</div>
    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}"><i class="fa-solid fa-chart-column"></i><span>Reports</span></a>
    <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
      <i class="fa-solid fa-bell"></i><span>Notifications</span>
      @php $adminUnread = auth('admin')->user()?->notifications()->unread()->count(); @endphp
      @if($adminUnread) <span class="badge rounded-pill bg-danger">{{ $adminUnread }}</span> @endif
    </a>
    <div class="nav-section-label">Account</div>
    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
    <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="fa-solid fa-user"></i><span>Profile</span></a>
  </nav>
  <div class="side-footer">
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="btn btn-link p-0 text-decoration-none"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></button>
    </form>
  </div>
</aside>
