@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Dashboard</span></nav>
    <h2>Welcome back, {{ explode(' ', auth('admin')->user()->name)[0] }} 👋</h2>
    <p id="liveClock"></p>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.applications') }}" class="btn btn-sumas-outline btn-sm"><i class="fa-solid fa-file-lines me-2"></i>Review Applications</a>
    <a href="{{ route('admin.allocation') }}" class="btn btn-sumas-primary btn-sm"><i class="fa-solid fa-key me-2"></i>Allocate Rooms</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon brown"><i class="fa-solid fa-user-graduate"></i></div></div><h3 data-counter="{{ $totalStudents }}">{{ $totalStudents }}</h3><div class="label">Total Students</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon gold"><i class="fa-solid fa-building"></i></div></div><h3 data-counter="{{ $totalHostels }}">{{ $totalHostels }}</h3><div class="label">Total Hostels</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon blue"><i class="fa-solid fa-door-closed"></i></div></div><h3 data-counter="{{ $totalRooms }}">{{ $totalRooms }}</h3><div class="label">Total Rooms</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon red"><i class="fa-solid fa-door-closed"></i></div></div><h3 data-counter="{{ $occupiedRooms }}">{{ $occupiedRooms }}</h3><div class="label">Occupied Rooms</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-door-open"></i></div></div><h3 data-counter="{{ $availableRooms }}">{{ $availableRooms }}</h3><div class="label">Available Rooms</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon orange"><i class="fa-solid fa-hourglass-half"></i></div></div><h3 data-counter="{{ $pendingApplications }}">{{ $pendingApplications }}</h3><div class="label">Pending Applications</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-circle-check"></i></div></div><h3 data-counter="{{ $approvedApplications }}">{{ $approvedApplications }}</h3><div class="label">Approved Applications</div></div></div>
  <div class="col-6 col-md-4 col-xl-3"><div class="stat-card"><div class="top"><div class="icon red"><i class="fa-solid fa-circle-xmark"></i></div></div><h3 data-counter="{{ $rejectedApplications }}">{{ $rejectedApplications }}</h3><div class="label">Rejected Applications</div></div></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-head"><div><h5>Occupancy Overview</h5><span class="sub">Beds occupied vs available, by hostel block</span></div></div>
      <div class="chart-box"><canvas id="occupancyChart"></canvas></div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="panel">
      <div class="panel-head"><div><h5>Application Status</h5><span class="sub">Current session breakdown</span></div></div>
      <div class="chart-box sm"><canvas id="statusChart"></canvas></div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-head"><div><h5>Monthly Allocation Trend</h5><span class="sub">Rooms allocated per month</span></div></div>
      <div class="chart-box"><canvas id="allocationChart"></canvas></div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="panel h-100">
      <div class="panel-head"><div><h5>Calendar</h5><span class="sub">Hostel office schedule</span></div></div>
      <div id="miniCalendar"></div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="panel">
      <div class="panel-head"><h5>Recent Activities</h5><a href="{{ route('admin.reports') }}" class="small text-brown fw-600">View all</a></div>
      @forelse($recentActivities as $activity)
        <div class="activity-item">
          <div class="dot" style="background:var(--sumas-{{ $activity['type'] }}-bg);color:var(--sumas-{{ $activity['type'] }});"><i class="fa-solid {{ $activity['icon'] }}"></i></div>
          <div><p>{{ $activity['text'] }}</p><small>{{ \Illuminate\Support\Carbon::parse($activity['time'])->diffForHumans() }}</small></div>
        </div>
      @empty
        <p class="text-muted small">No recent activity yet.</p>
      @endforelse
    </div>
  </div>
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-head"><h5>Recent Applications</h5><a href="{{ route('admin.applications') }}" class="small text-brown fw-600">View all</a></div>
      <div class="table-responsive">
        <table class="table table-sumas mb-0">
          <thead><tr><th>Student</th><th>Hostel</th><th>Date</th><th>Status</th><th></th></tr></thead>
          <tbody>
            @forelse($recentApplications as $app)
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $app->user->initials() }}</div><div><div class="fw-600 small">{{ $app->user->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $app->user->matric_number }}</div></div></td>
              <td>{{ $app->hostel->name }}</td><td>{{ $app->created_at->format('M d, Y') }}</td>
              <td><span class="badge-status {{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
              <td class="table-actions"><a href="{{ route('admin.applications') }}" class="btn btn-light btn-sm"><i class="fa-solid fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No applications yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
<script>
Chart.defaults.font.family = "'Poppins', sans-serif";
Chart.defaults.color = '#6E6660';

new Chart(document.getElementById('occupancyChart'), {
  type: 'bar',
  data: {
    labels: @json($occupancyLabels),
    datasets: [
      { label: 'Occupied', data: @json($occupiedSeries), backgroundColor: '#5D4037', borderRadius: 6, maxBarThickness: 34 },
      { label: 'Available', data: @json($availableSeries), backgroundColor: '#C89B3C', borderRadius: 6, maxBarThickness: 34 }
    ]
  },
  options: { responsive:true, maintainAspectRatio:false, scales: { x: { grid: { display:false } }, y: { grid: { color:'#EFE8DC' }, beginAtZero:true } }, plugins: { legend: { position:'bottom', labels:{ boxWidth:10, usePointStyle:true } } } }
});

new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: { labels: ['Approved','Pending','Rejected'], datasets: [{ data: [{{ $approvedApplications }}, {{ $pendingApplications }}, {{ $rejectedApplications }}], backgroundColor:['#2E7D32','#C87A0A','#C62828'], borderWidth:0 }] },
  options: { responsive:true, maintainAspectRatio:false, cutout:'68%', plugins:{ legend:{ position:'bottom', labels:{ boxWidth:10, usePointStyle:true } } } }
});

new Chart(document.getElementById('allocationChart'), {
  type: 'line',
  data: {
    labels: @json($monthlyAllocations->pluck('label')),
    datasets: [{ label: 'Rooms Allocated', data: @json($monthlyAllocations->pluck('count')), borderColor:'#C89B3C', backgroundColor:'rgba(200,155,60,0.15)', fill:true, tension:0.4, pointBackgroundColor:'#5D4037' }]
  },
  options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ x:{ grid:{ display:false } }, y:{ grid:{ color:'#EFE8DC' } } } }
});
</script>
@endpush
