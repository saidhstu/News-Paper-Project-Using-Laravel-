<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'BizzNieuws') }}</title>
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>
	<body>
		<div class="rootContainer">
			<header class="scrolling">
				<div class="navbar-fixed">
					<nav>
						<div class="nav-wrapper">
							<a class="brand-logo" href="{{ route('index') }}">
								<img class="hide-on-small-and-down" height="32px" src="{{ asset('image/logo.png')  }}">
							</a>
							<a onclick="return false" data-target="NavMenu" class="sidenav-trigger button-collapse"><i class="material-icons">menu</i></a>
							<ul class="right hide-on-med-and-down">
								@include('layouts.navlinks')
								@yield('navlinks')
							</ul>
						</div>
						<ul id="NavMenu" class="sidenav">
							@yield('navlinks')
						</ul>
					</nav>
				</div>
			</header>
			<main>
				<div class="container mainContent">
					<div class="row">
						@if(Session('msgError'))
							<div class="col s12 m8 offset-m2 xl6 offset-xl3">
								<div class="card-panel red white-text">
									{{ Session('msgError') }}
								</div>
							</div>
						@endif
						@if(Session('msgWarning'))
							<div class="col s12 m8 offset-m2 xl6 offset-xl3">
								<div class="card-panel orange white-text">
									{{ Session('msgWarning') }}
								</div>
							</div>
						@endif
						@if(Session('msgSuccess'))
							<div class="col s12 m8 offset-m2 xl6 offset-xl3">
								<div class="card-panel green white-text">
									{{ Session('msgSuccess') }}
								</div>
							</div>
						@endif
						@if(Session('msgInfo'))
							<div class="col s12 m8 offset-m2 xl6 offset-xl3">
								<div class="card-panel bizzblue white-text">
									{{ Session('msgInfo') }}
								</div>
							</div>
						@endif
						@php(\App\Utils\Utils::flashReset())
					</div>
					@yield('content')
				</div>
			</main>
		</div>
	</body>
	<script>window.BASEURI = "{{ \Illuminate\Support\Facades\URL::to('/') }}"</script>
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	@yield('scripts')
</html>