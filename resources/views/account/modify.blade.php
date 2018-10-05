@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('account') }}"><i class="material-icons">arrow_back</i></a>
				<form method="POST" action="{{ route('accountModifySubmit') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">account_circle</i>
						<input type="text" id="name" name="name" value="{{ $name }}" required="required" autofocus="autofocus"/>
						<label for="name">@lang('account.name')</label>
						@if($errors->has('name'))
							<span class="helper-text red-text">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">email</i>
						<input type="email" id="email" name="email" value="{{ $email }}" required="required" autofocus="autofocus" class="validate"/>
						<label for="email">@lang('account.email')</label>
						@if($errors->has('email'))
							<span class="helper-text red-text">{{ $errors->first('email') }}</span>
						@endif
					</div>
					<div class="input-field center-align">
						<button type="submit" class="btn btn-primary">@lang('account.modifySubmit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection