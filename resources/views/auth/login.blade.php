@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email" name="email" value="{{ old('email') }}" required="required" autofocus="autofocus" class="validate"/>
						<label for="email">@lang('account.email')</label>
						@if($errors->has('email'))
							<span class="helper-text red-text">{{ $errors->first('email') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">lock</i>
						<input type="password" id="password" name="password" value="{{ old('password') }}" required="required"/>
						<label for="password">@lang('account.password')</label>
						@if($errors->has('password'))
							<span class="helper-text red-text">{{ $errors->first('password') }}</span>
						@endif
					</div>
					<div class="input-field">
						<label for="remember">
							<input id="remember" class="filled-in" type="checkbox" name="remember" {{ old('remember') ? 'checked="checked"' : '' }}/>
							<span>@lang('account.remember')</span>
						</label>
					</div>
					<br>
					<br>
					<div class="input-field center-align">
						<button type="submit" class="btn">@lang('account.loginSubmit')</button>
						<a class="btn" href="{{ route('password.request') }}">@lang('account.loginPasswordReset')</a>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection