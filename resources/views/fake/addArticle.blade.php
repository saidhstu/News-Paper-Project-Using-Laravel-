@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('admin') }}"><i class="material-icons">arrow_back</i></a>
				<br><br>
				<form method="POST" action="{{ route('adminFakeAddArticleSubmit') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">bookmark</i>
						<input type="text" name="title" id="title" required="required" data-length="75" class="charCount"/>
						<label for="title">@lang('admin.fake.addArticle.title')</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">label</i>
						<input type="text" name="description" id="description" required="required" data-length="250" class="charCount"/>
						<label for="description">@lang('admin.fake.addArticle.description')</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">face</i>
						<input type="text" name="author" id="author" required="required"/>
						<label for="author">@lang('admin.fake.addArticle.author')</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">image</i>
						<input type="text" name="imageURL" id="imageURL"/>
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
					<br>
					<div class="input-field">
						<textarea name="article" id="fakeFeedBox"></textarea>
					</div>
					<input type="hidden" name="feed" value="{{ $theFeed }}">
					<div class="input-field center-align">
						<button type="submit" class="btn">@lang('admin.fake.addArticle.submit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection