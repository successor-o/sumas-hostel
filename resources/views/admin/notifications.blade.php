@extends('layouts.dashboard')
@section('title', 'Notifications')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Notifications</span></nav>
    <h2>Notification Center</h2>
    <p class="text-muted small mb-0">System alerts and hostel activity updates.</p>
  </div>
  <form method="POST" action="{{ route('admin.notifications.read') }}">
    @csrf
    <button type="submit" class="btn btn-sumas-outline btn-sm"><i class="fa-solid fa-check-double me-2"></i>Mark All as Read</button>
  </form>
</div>

<div class="panel">
  <ul class="nav nav-tabs nav-tabs-sumas mb-3">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#allNotif">All</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#successNotif">Success</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#warningNotif">Warnings</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#errorNotif">Errors</button></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="allNotif">
      @forelse($notifications as $n)
        <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}">
          <div class="icon" style="background:var(--sumas-{{ $n->type }}-bg);color:var(--sumas-{{ $n->type }});"><i class="fa-solid {{ $n->iconFor() }}"></i></div>
          <div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->diffForHumans() }}</small></div>
        </div>
      @empty
        <p class="text-muted small text-center py-4 mb-0">No notifications yet.</p>
      @endforelse
    </div>
    <div class="tab-pane fade" id="successNotif">
      @foreach($notifications->where('type', 'success') as $n)
        <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}"><div class="icon" style="background:var(--sumas-success-bg);color:var(--sumas-success);"><i class="fa-solid {{ $n->iconFor() }}"></i></div><div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->diffForHumans() }}</small></div></div>
      @endforeach
    </div>
    <div class="tab-pane fade" id="warningNotif">
      @foreach($notifications->where('type', 'warning') as $n)
        <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}"><div class="icon" style="background:var(--sumas-warning-bg);color:var(--sumas-warning);"><i class="fa-solid {{ $n->iconFor() }}"></i></div><div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->diffForHumans() }}</small></div></div>
      @endforeach
    </div>
    <div class="tab-pane fade" id="errorNotif">
      @foreach($notifications->where('type', 'danger') as $n)
        <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}"><div class="icon" style="background:var(--sumas-danger-bg);color:var(--sumas-danger);"><i class="fa-solid {{ $n->iconFor() }}"></i></div><div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->diffForHumans() }}</small></div></div>
      @endforeach
    </div>
  </div>
</div>
@endsection
