@section('navlinks')
	@if(!isset($activeLink))@php $activeLink = Route::currentRouteName();@endphp@endif
	<li @if($activeLink === 'feeds')class="active"@endif>
		<a href="{{ route('feeds') }}">@lang('index.feeds')</a>
	</li>
	@guest
		<li @if($activeLink === 'login')class="active"@endif>
			<a href="{{ route('login') }}">@lang('index.login')</a>
		</li>
		<li @if($activeLink === 'register')class="active"@endif>
			<a href="{{ route('register') }}">@lang('index.register')</a>
		</li>
	@else
		@if(\App\Utils\Utils::isUserAdmin())
			<li @if($activeLink === 'admin')class="active"@endif>
				<a href="{{ route('admin') }}">@lang('index.admin')</a>
			</li>
		@endif
		<li @if($activeLink === 'account')class="active"@endif>
			<a href="{{ route('account') }}">@lang('index.account')</a>
		</li>
		<li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">@lang('index.logout')</a></li>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
	@endguest
@endsection