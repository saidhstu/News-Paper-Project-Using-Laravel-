@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('account') }}"><i class="material-icons">arrow_back</i></a>
				<form method="POST" action="{{ route('accountModifyPasswordSubmit') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">lock</i>
						<input type="password" id="old_password" name="old_password" value="{{ old('old_password') }}" required="required"/>
						<label for="old_password">@lang('account.oldPassword')</label>
						@if($errors->has('old_password'))
							<span class="helper-text red-text">{{ $errors->first('old_password') }}</span>
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
						<i class="material-icons prefix">lock</i>
						<input type="password" id="password-confirm" name="password_confirmation" required="required"/>
						<label for="password-confirm">@lang('account.passwordConfirm')</label>
					</div>
					<div class="input-field center-align">
						<button type="submit" class="btn btn-primary">@lang('account.modifyPasswordSubmit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection