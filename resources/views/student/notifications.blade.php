@extends('layouts.dashboard')
@section('title', 'Notifications')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Notifications</span></nav>
    <h2>Notifications</h2>
    <p class="text-muted small mb-0">Updates about your hostel application and stay.</p>
  </div>
  <form method="POST" action="{{ route('student.notifications.read') }}">
    @csrf
    <button type="submit" class="btn btn-sumas-outline btn-sm"><i class="fa-solid fa-check-double me-2"></i>Mark All as Read</button>
  </form>
</div>

<div class="panel">
  @forelse($notifications as $n)
    <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}">
      <div class="icon" style="background:var(--sumas-{{ $n->type }}-bg);color:var(--sumas-{{ $n->type }});"><i class="fa-solid {{ $n->iconFor() }}"></i></div>
      <div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->format('F j, Y') }}</small></div>
    </div>
  @empty
    <p class="text-muted small text-center py-4 mb-0">You have no notifications yet.</p>
  @endforelse
</div>
@endsection
