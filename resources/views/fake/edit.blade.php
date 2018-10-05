@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('adminFakeEditSubmit', $feed->id) }}">
					<a class="btn btn-floating" href="{{ route('feedsInfo', $feed->id) }}"><i class="material-icons">arrow_back</i></a>
					<button class="btn btn-floating " type="submit"><i class="material-icons">save</i></button>
					<br><br>
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">comment</i>
						<input type="text" id="name" name="name" value="{{ $feed->name }}" required="required" autofocus="autofocus"/>
						<label for="name">@lang('admin.fake.add.placeholderName')</label>
						@if($errors->has('name'))
							<span class="helper-text red-text">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">description</i>
						<input type="text" id="description" name="description" value="{{ $feed->description }}" required="required"/>
						<label for="description">@lang('admin.fake.add.placeholderDescription')</label>
						@if($errors->has('description'))
							<span class="helper-text red-text">{{ $errors->first('description') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">copyright</i>
						<input type="text" id="copyright" name="copyright" value="{{ $feed->copyright }}" required="required"/>
						<label for="copyright">@lang('admin.fake.add.placeholderCopyright')</label>
						@if($errors->has('copyright'))
							<span class="helper-text red-text">{{ $errors->first('copyright') }}</span>
						@endif
					</div>
					<div class="input-field">
						<i class="material-icons prefix">reorder</i>
						<select name="category" id="category">
							@foreach($categories as $category)
								@if($feed->category_id === $category->id)
									<option value="{{ $category->id }}" selected="selected">@lang('category.' . $category->name)</option>
								@else
									<option value="{{ $category->id }}">@lang('category.' . $category->name)</option>
								@endif
							@endforeach
						</select>
						<label for="category">@lang('admin.fake.add.category')</label>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection