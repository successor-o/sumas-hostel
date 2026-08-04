<aside class="dash-sidebar">
  <div class="side-brand">
    <img src="{{ asset('assets/images/sumas-logo.png') }}" alt="SUMAS Logo">
    <div class="brand-text"><span class="name">SUMAS Hostels</span><span class="role">Student Portal</span></div>
  </div>
  <nav class="side-nav">
    <div class="nav-section-label">Overview</div>
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
    <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"><i class="fa-solid fa-user"></i><span>Profile</span></a>
    <div class="nav-section-label">Hostel</div>
    <a href="{{ route('student.application') }}" class="nav-link {{ request()->routeIs('student.application') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i><span>Hostel Application</span></a>
    <a href="{{ route('student.allocation') }}" class="nav-link {{ request()->routeIs('student.allocation') ? 'active' : '' }}"><i class="fa-solid fa-key"></i><span>My Allocation</span></a>
    <a href="{{ route('student.rules') }}" class="nav-link {{ request()->routeIs('student.rules') ? 'active' : '' }}"><i class="fa-solid fa-book"></i><span>Hostel Rules</span></a>
    <div class="nav-section-label">Account</div>
    <a href="{{ route('student.notifications') }}" class="nav-link {{ request()->routeIs('student.notifications') ? 'active' : '' }}">
      <i class="fa-solid fa-bell"></i><span>Notifications</span>
      @php $unread = auth('web')->user()?->notifications()->unread()->count(); @endphp
      @if($unread) <span class="badge rounded-pill bg-danger">{{ $unread }}</span> @endif
    </a>
    <a href="{{ route('student.settings') }}" class="nav-link {{ request()->routeIs('student.settings') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
  </nav>
  <div class="side-footer">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-link p-0 text-decoration-none"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></button>
    </form>
  </div>
</aside>
