@extends('layouts.dashboard')
@section('title', 'Occupancy Monitoring')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Occupancy Monitoring</span></nav>
    <h2>Occupancy Monitoring</h2>
    <p class="text-muted small mb-0">Live bed-level occupancy across every hostel block.</p>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon brown"><i class="fa-solid fa-bed"></i></div></div><h3>{{ $totalBeds }}</h3><div class="label">Total Beds</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon red"><i class="fa-solid fa-user-check"></i></div></div><h3>{{ $occupiedBeds }}</h3><div class="label">Beds Occupied</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-bed"></i></div></div><h3>{{ $availableBeds }}</h3><div class="label">Beds Available</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon gold"><i class="fa-solid fa-chart-pie"></i></div></div><h3>{{ $overallOccupancy }}%</h3><div class="label">Overall Occupancy</div></div></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-6"><div class="panel"><div class="panel-head"><h5>Occupancy by Block</h5></div><div class="chart-box"><canvas id="occByBlock"></canvas></div></div></div>
  <div class="col-lg-6"><div class="panel"><div class="panel-head"><h5>Occupancy Trend (Last 6 Months)</h5></div><div class="chart-box"><canvas id="occTrend"></canvas></div></div></div>
</div>

<div class="panel">
  <div class="panel-head"><h5>Block-Level Detail</h5></div>
  <div class="table-responsive">
    <table class="table table-sumas align-middle mb-0">
      <thead><tr><th>Hostel Block</th><th>Total Beds</th><th>Occupied</th><th>Available</th><th>Occupancy</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($hostels as $hostel)
        <tr>
          <td class="fw-600">{{ $hostel->name }}</td><td>{{ $hostel->totalBeds() }}</td><td>{{ $hostel->occupiedBeds() }}</td><td>{{ $hostel->availableBeds() }}</td>
          <td style="min-width:140px;"><div class="occ-bar"><div class="fill" style="width:{{ $hostel->occupancyPercent() }}%;"></div></div></td>
          <td><span class="badge-status {{ $hostel->occupancyPercent() >= 100 ? 'full' : ($hostel->occupancyPercent() >= 80 ? 'pending' : 'active') }}">{{ $hostel->occupancyPercent() }}%</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
new Chart(document.getElementById('occByBlock'), {
  type: 'polarArea',
  data: { labels: @json($hostels->pluck('name')), datasets:[{ data: @json($hostels->map(fn($h) => $h->occupancyPercent())), backgroundColor:['#5D4037','#8D6E63','#C89B3C','#A67C2E','#3E2723','#E8C97A'] }] },
  options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ boxWidth:10, usePointStyle:true } } } }
});
new Chart(document.getElementById('occTrend'), {
  type: 'line',
  data: { labels: @json($trend->pluck('label')), datasets:[{ label:'Occupancy %', data: @json($trend->pluck('value')), borderColor:'#5D4037', backgroundColor:'rgba(93,64,55,0.12)', fill:true, tension:0.4, pointBackgroundColor:'#C89B3C' }] },
  options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{ min:0, max:100, grid:{ color:'#EFE8DC' } }, x:{ grid:{ display:false } } } }
});
</script>
@endpush
