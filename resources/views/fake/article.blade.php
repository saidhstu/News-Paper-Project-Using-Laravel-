@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="javascript:history.back()"><i class="material-icons">arrow_back</i></a>
		<span class="cardTitle">{{ $item->title }}</span>
		<br>
		<p>@lang('feeds.article.author'): {{ $item->author }}</p>
		<p>@lang('feeds.article.date'): {{ $item->pubDate }}</p>
		<hr>
		@if($article->image)
			<img src="{{ $article->image }}">
			<hr>
		@endif
		<div class="articleBox">
			{!! html_entity_decode($article->content) !!}
		</div>
	</div>
@endsection