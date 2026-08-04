<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') | SUMAS Hostel Management System</title>
<link rel="icon" type="image/png" href="{{ asset('assets/images/sumas-logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@stack('styles')
</head>
<body class="dash-body">
<div class="sidebar-overlay"></div>

@php $area = request()->routeIs('admin.*') ? 'admin' : 'student'; @endphp
@include('partials.sidebar-'.$area)

<div class="dash-main">
  @include('partials.topbar')

  <div class="dash-content">
    @if(session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if($errors->any() && !$errors->has('login') && !$errors->has('email'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
  </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index:1090;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('head-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@stack('scripts')
</body>
</html>
