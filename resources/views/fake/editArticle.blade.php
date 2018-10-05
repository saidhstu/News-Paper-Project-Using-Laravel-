@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('adminFakeEditArticleSubmit', $item->id) }}" enctype="multipart/form-data">
					<a class="btn btn-floating" href="{{ route('adminFakeEditArticle', $item->feed_id) }}"><i class="material-icons">arrow_back</i></a>
					<button class="btn btn-floating" type="submit"><i class="material-icons">save</i></button>
					<a class="btn btn-floating" href="{{ route('adminFakeEditArticleImage', $item->id) }}"><i class="material-icons">image</i></a>
					<a class="btn btn-floating red" href="{{ route('adminFakeEditArticleDelete', $item->id) }}"><i class="material-icons">delete</i></a>
					<br><br>
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">bookmark</i>
						<input type="text" name="title" id="title" required="required" data-length="75" class="charCount" value="{{ $item->title }}"/>
						<label for="title">@lang('admin.fake.editArticle.editTitle')</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">label</i>
						<input type="text" name="description" id="description" required="required" data-length="250" class="charCount" value="{{ $item->description }}"/>
						<label for="description">@lang('admin.fake.editArticle.description')</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">face</i>
						<input type="text" name="author" id="author" required="required" value="{{ $item->author }}"/>
						<label for="author">@lang('admin.fake.editArticle.author')</label>
					</div>
					<div class="input-field">
						<textarea name="article" id="fakeFeedBox">{!! $article->content !!}</textarea>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection