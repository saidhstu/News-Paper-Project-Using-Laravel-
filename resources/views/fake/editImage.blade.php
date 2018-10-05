@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('adminFakeEditArticleImageReal', $articleId) }}" enctype="multipart/form-data">
					<a class="btn btn-floating" href="{{ route('adminFakeEditArticleReal', $articleId) }}"><i class="material-icons">arrow_back</i></a>
					<button class="btn btn-floating" type="submit"><i class="material-icons">save</i></button>
					<a class="btn btn-floating red" href="{{ route('adminFakeEditArticleImageDelete', $articleId) }}"><i class="material-icons">delete</i></a>
					<br><br>
					@if($hasImage)
						<p><strong>@lang('admin.fake.editImage.current')</strong></p>
						<img src="{{ $imageUrl }}" style="max-width: 100%;">
						<hr>
					@endif
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">image</i>
						@if (isset($imageURL) && !$imageLocal)
							<input type="text" name="imageURL" id="imageURL" value="{{ $imageURL }}"/>
						@else
							<input type="text" name="imageURL" id="imageURL"/>
						@endif
						<label for="imageURL">@lang('admin.fake.addArticle.imageURL')</label>
					</div>
					<div class="input-field file-field">
						<div class="btn">
							<span>@lang('admin.fake.addArticle.imageFileButton')</span>
							<input type="file" name="imageFile"/>
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection