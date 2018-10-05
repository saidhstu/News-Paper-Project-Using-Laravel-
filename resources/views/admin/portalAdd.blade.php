@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('adminPortal') }}"><i class="material-icons">arrow_back</i></a>
				<form method="POST" action="{{ route('adminPortalAddSubmit') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">receipt</i>
						<input type="text" id="name" name="name" value="{{ old('name') }}" required="required" autofocus="autofocus"/>
						<label for="name">@lang('admin.portal.add.placeholder')</label>
						@if($errors->has('name'))
							<span class="helper-text red-text">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="input-field center-align">
						<button type="submit" class="btn">@lang('admin.portal.add.submit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection