@extends('layouts.dashboard')
@section('title', 'Messages')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Messages</span></nav>
    <h2>Contact Messages</h2>
    <p class="text-muted small mb-0">Messages submitted through the public contact form.</p>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-4"><div class="stat-card"><div class="top"><div class="icon blue"><i class="fa-solid fa-envelope-open-text"></i></div></div><h3>{{ $totalCount }}</h3><div class="label">Total Messages</div></div></div>
  <div class="col-6 col-lg-4"><div class="stat-card"><div class="top"><div class="icon orange"><i class="fa-solid fa-envelope"></i></div></div><h3>{{ $unreadCount }}</h3><div class="label">Unread</div></div></div>
  <div class="col-6 col-lg-4"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-envelope-open"></i></div></div><h3>{{ $totalCount - $unreadCount }}</h3><div class="label">Read</div></div></div>
</div>

<div class="panel">
  <form method="GET" action="{{ route('admin.messages') }}" class="panel-head">
    <div class="row g-2 w-100 align-items-center">
      <div class="col-lg-4"><div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, email, subject or matric..."></div></div>
      <div class="col-lg-auto d-flex gap-2">
        <a href="{{ route('admin.messages') }}" class="btn btn-sm {{ !request('status') ? 'btn-sumas-primary' : 'btn-light' }}">All</a>
        <a href="{{ route('admin.messages', ['status' => 'unread']) }}" class="btn btn-sm {{ request('status') === 'unread' ? 'btn-sumas-primary' : 'btn-light' }}">Unread</a>
        <a href="{{ route('admin.messages', ['status' => 'read']) }}" class="btn btn-sm {{ request('status') === 'read' ? 'btn-sumas-primary' : 'btn-light' }}">Read</a>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sumas align-middle mb-0">
      <thead><tr><th>Sender</th><th>Subject</th><th>Message</th><th>Received</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @forelse($messages as $message)
        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="table-avatar">{{ strtoupper(substr($message->name, 0, 2)) }}</div>
              <div>
                <span class="fw-600 small d-block">{{ $message->name }}</span>
                <small class="text-muted">{{ $message->matric_number ?: $message->email }}</small>
              </div>
            </div>
          </td>
          <td class="small">{{ $message->subject }}</td>
          <td class="small text-muted" style="max-width:280px;"><div class="text-truncate">{{ $message->message }}</div></td>
          <td class="small text-nowrap">{{ $message->created_at->format('M j, Y') }}<br><small class="text-muted">{{ $message->created_at->format('g:i A') }}</small></td>
          <td><span class="badge-status {{ $message->is_read ? 'active' : 'pending' }}">{{ $message->is_read ? 'Read' : 'Unread' }}</span></td>
          <td class="table-actions">
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewMessageModal{{ $message->id }}" title="View"><i class="fa-solid fa-eye"></i></button>
            @if($message->is_read)
              <form method="POST" action="{{ route('admin.messages.unread', $message) }}" class="d-inline">
                @csrf
                <button class="btn btn-light btn-sm" title="Mark as unread"><i class="fa-solid fa-envelope"></i></button>
              </form>
            @else
              <form method="POST" action="{{ route('admin.messages.read', $message) }}" class="d-inline">
                @csrf
                <button class="btn btn-light btn-sm" title="Mark as read"><i class="fa-solid fa-envelope-open"></i></button>
              </form>
            @endif
            <button class="btn btn-light btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#deleteMessageModal{{ $message->id }}" title="Delete"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">No messages found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <span class="small text-muted">Showing {{ $messages->firstItem() ?? 0 }}&ndash;{{ $messages->lastItem() ?? 0 }} of {{ $messages->total() }} messages</span>
    <div>{{ $messages->onEachSide(1)->links() }}</div>
  </div>
</div>

@foreach($messages as $message)
<div class="modal fade" id="viewMessageModal{{ $message->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">{{ $message->subject }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="table-avatar" style="width:52px;height:52px;font-size:1.1rem;">{{ strtoupper(substr($message->name, 0, 2)) }}</div>
          <div>
            <strong class="d-block">{{ $message->name }}</strong>
            <small class="text-muted">{{ $message->email }}</small>
            @if($message->matric_number)<small class="text-muted d-block">{{ $message->matric_number }}</small>@endif
          </div>
          <span class="ms-auto badge-status {{ $message->is_read ? 'active' : 'pending' }}">{{ $message->is_read ? 'Read' : 'Unread' }}</span>
        </div>
        <div class="border rounded-3 p-3 bg-light small"><p class="mb-0" style="white-space:pre-line;">{{ $message->message }}</p></div>
        <small class="text-muted d-block mt-2">Received {{ $message->created_at->format('M j, Y \a\t g:i A') }}</small>
      </div>
      <div class="modal-footer">
        @if(! $message->is_read)
          <form method="POST" action="{{ route('admin.messages.read', $message) }}">@csrf<button type="submit" class="btn btn-sumas-primary"><i class="fa-solid fa-envelope-open me-2"></i>Mark as Read</button></form>
        @else
          <form method="POST" action="{{ route('admin.messages.unread', $message) }}">@csrf<button type="submit" class="btn btn-sumas-outline"><i class="fa-solid fa-envelope me-2"></i>Mark as Unread</button></form>
        @endif
        <button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteMessageModal{{ $message->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px;background:var(--sumas-danger-bg);color:var(--sumas-danger);font-size:1.6rem;"><i class="fa-solid fa-trash"></i></div>
        <h5>Delete this message?</h5>
        <p class="text-muted small">This will permanently remove the message from <strong>{{ $message->name }}</strong>. This action cannot be undone.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button>
          <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-danger text-white">Yes, Delete</button></form>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
