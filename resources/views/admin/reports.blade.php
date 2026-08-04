@extends('layouts.dashboard')
@section('title', 'Reports')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Reports</span></nav>
    <h2>Reports</h2>
    <p class="text-muted small mb-0">Generate and export reports across students, hostels, and allocations.</p>
  </div>
</div>

<ul class="nav nav-tabs nav-tabs-sumas mb-4">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#repStudent">Student Reports</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#repHostel">Hostel Reports</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#repOccupancy">Occupancy Reports</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#repAllocation">Allocation Reports</button></li>
</ul>

<div class="tab-content">
  <div class="tab-pane fade show active" id="repStudent">
    <div class="row g-3 mb-3">
      <div class="col-lg-8"><div class="panel"><div class="panel-head"><div><h5>Students by Faculty</h5><span class="sub">Current session distribution</span></div><button class="btn btn-sumas-outline btn-sm" onclick="window.print()"><i class="fa-solid fa-file-arrow-down me-2"></i>Export PDF</button></div><div class="chart-box"><canvas id="studByFaculty"></canvas></div></div></div>
      <div class="col-lg-4"><div class="panel"><div class="panel-head"><h5>Housing Status</h5></div><div class="chart-box sm"><canvas id="studHousing"></canvas></div></div></div>
    </div>
  </div>

  <div class="tab-pane fade" id="repHostel">
    <div class="panel">
      <div class="panel-head"><div><h5>Hostel Capacity Report</h5><span class="sub">Beds, rooms, and utilisation per block</span></div><button class="btn btn-sumas-outline btn-sm" onclick="window.print()"><i class="fa-solid fa-file-arrow-down me-2"></i>Export CSV</button></div>
      <div class="table-responsive">
        <table class="table table-sumas align-middle mb-0">
          <thead><tr><th>Hostel Block</th><th>Rooms</th><th>Beds</th><th>Occupied</th><th>Utilisation</th></tr></thead>
          <tbody>
            @foreach($hostels as $h)
            <tr><td class="fw-600">{{ $h->name }}</td><td>{{ $h->rooms_count }}</td><td>{{ $h->totalBeds() }}</td><td>{{ $h->occupiedBeds() }}</td><td>{{ $h->occupancyPercent() }}%</td></tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="repOccupancy">
    <div class="panel">
      <div class="panel-head"><div><h5>Occupancy Rate Over Time</h5><span class="sub">Session-wide average</span></div><button class="btn btn-sumas-outline btn-sm" onclick="window.print()"><i class="fa-solid fa-file-arrow-down me-2"></i>Export PDF</button></div>
      <div class="chart-box"><canvas id="occReport"></canvas></div>
    </div>
  </div>

  <div class="tab-pane fade" id="repAllocation">
    <div class="panel">
      <div class="panel-head"><div><h5>Monthly Allocations</h5><span class="sub">Rooms allocated per month, current session</span></div><button class="btn btn-sumas-outline btn-sm" onclick="window.print()"><i class="fa-solid fa-file-arrow-down me-2"></i>Export CSV</button></div>
      <div class="chart-box"><canvas id="allocReport"></canvas></div>
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
new Chart(document.getElementById('studByFaculty'), {
  type:'bar',
  data:{ labels: @json($facultyBreakdown->keys()), datasets:[{ data: @json($facultyBreakdown->values()), backgroundColor:'#5D4037', borderRadius:6, maxBarThickness:40 }] },
  options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{ grid:{color:'#EFE8DC'} }, x:{ grid:{display:false} } } }
});
new Chart(document.getElementById('studHousing'), {
  type:'doughnut',
  data:{ labels:['Housed','Not Housed'], datasets:[{ data:[{{ $housedCount }}, {{ $notHousedCount }}], backgroundColor:['#2E7D32','#C87A0A'], borderWidth:0 }] },
  options:{ responsive:true, maintainAspectRatio:false, cutout:'68%', plugins:{legend:{position:'bottom', labels:{boxWidth:10, usePointStyle:true}}} }
});
new Chart(document.getElementById('occReport'), {
  type:'line',
  data:{ labels: @json($occupancyTrend->pluck('label')), datasets:[{ label:'Occupancy %', data: @json($occupancyTrend->pluck('value')), borderColor:'#C89B3C', backgroundColor:'rgba(200,155,60,0.15)', fill:true, tension:0.4 }] },
  options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{ min:0, max:100, grid:{color:'#EFE8DC'} }, x:{ grid:{display:false} } } }
});
new Chart(document.getElementById('allocReport'), {
  type:'bar',
  data:{ labels: @json($monthlyAllocations->pluck('label')), datasets:[{ label:'Allocated', data: @json($monthlyAllocations->pluck('count')), backgroundColor:'#8D6E63', borderRadius:6, maxBarThickness:34 }] },
  options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{ grid:{color:'#EFE8DC'} }, x:{ grid:{display:false} } } }
});
</script>
@endpush
