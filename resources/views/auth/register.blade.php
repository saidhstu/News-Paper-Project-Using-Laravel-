@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('register') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">account_circle</i>
						<input type="text" id="name" name="name" value="{{ old('name') }}" required="required" autofocus="autofocus"/>
						<label for="name">@lang('account.fullName')</label>
						@if($errors->has('name'))
							<span class="helper-text red-text">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email" name="email" value="{{ old('email') }}" required="required" class="validate"/>
						<label for="email">@lang('account.email')</label>
						@if($errors->has('email'))
							<span class="helper-text red-text">{{ $errors->first('email') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">lock</i>
						<input type="password" id="password" name="password" required="required"/>
						<label for="password">@lang('account.password')</label>
						@if($errors->has('password'))
							<span class="helper-text red-text">{{ $errors->first('password') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">lock</i>
						<input type="password" id="password-confirm" name="password_confirmation" required="required"/>
						<label for="password-confirm">@lang('account.passwordConfirm')</label>
					</div>
					<div class="input-field center-align">
						<button type="submit" class="btn">@lang('account.registerSubmit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection