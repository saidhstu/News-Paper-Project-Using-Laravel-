@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m9 l6 push-m3 push-l3">
			<div class="card-panel">
				@if ($itemCount > 0)
					<div class="center-align">
						@yield('pagination')
					</div>
					@if($hasBizzMail)
						<form method="POST" action="{{ route('integrationBizzMailSubmit') }}">
							{{ csrf_field() }}
							<button class="btn" type="submit">@lang('index.searchSend')</button>
							<br><br>
							@endif
							@foreach($items as $item)
								<div class="row">
									<div class="col s12 articleTitle">
										@if($hasBizzMail)
											<label>
												<input type="checkbox" class="filled-in" name="item[{{ $item->id }}]"/>
												<span></span>
											</label>
										@endif
										@if($item->scan)
											<b class="bizzblue-text"><a href="{{ $item->link }}" target="_blank">{{ $item->title }}</a></b>
										@else
											<b class="bizzblue-text"><a href="{{ $item->link }}">{{ $item->title }}</a></b>
										@endif
									</div>
									<div class="col s12">
										@if($item->hasImage())
											<div class="col s12 articleImageContainer center-align">
												<img class="left articleImage" src="{{ $item->image }}">
											</div>
										@endif
										<p class="articleText">{{ $item->description }}</p>
									</div>
									<div class="col s12">
										<span class="left">@lang('index.source'): {{ $item->feedName }}</span>
										<span class="right">{{ \Carbon\Carbon::parse($item->pubdate)->toDateString() }}</span>
									</div>
								</div>
								<hr>
							@endforeach
							@if ($hasBizzMail)
								<button class="btn" type="submit">@lang('index.searchSend')</button>
						</form>
					@endif
					<div class="center-align">
						@yield('pagination')
					</div>
				@else
					@lang('index.no_results')
				@endif
			</div>
		</div>
		<div class="col s12 m3 l3 pull-m9 pull-l6">
			<div class="card-panel">
				<span class="cardTitle">@lang('index.searchTitle')</span>
				<br>
				<strong class="bizzblue-text">@lang('index.portalLabel')</strong>
				<select name="portal" onchange="window.location='{{ url('/search') }}/' + this.value + '/0/'">
					<option value="-1">@lang('index.noPortal')</option>
					@foreach($portals as $thePortal)
						@if ($portal === $thePortal->id)
							<option value="{{ $thePortal->id }}" selected="selected" disabled="disabled">{{ $thePortal->name }}</option>
						@else
							<option value="{{ $thePortal->id }}">{{ $thePortal->name }}</option>
						@endif
					@endforeach
				</select>
				<strong class="bizzblue-text">@lang('index.rootCategoryLabel')</strong>
				<select name="rootCategory" onchange="window.location='{{ url("/search/$portal/0") }}/' + this.value">
					@foreach($rootCategories as $category)
						@if ($category->id === $rootCategoryId)
							<option value="{{ $category->id }}" selected="selected" disabled="disabled">@lang('category.' . $category->name)</option>
						@else
							<option value="{{ $category->id }}">@lang('category.' . $category->name)</option>
						@endif
					@endforeach
				</select>
				<strong class="bizzblue-text">@lang('index.categoryLabel')</strong>
				<select name="category" onchange="window.location='{{ url("/search/$portal/0") }}/' + this.value">
					@foreach($categories as $category)
						@if ($category->id === $categoryId)
							<option value="{{ $category->id }}" selected="selected" disabled="disabled">@lang('category.' . $category->name)</option>
						@else
							<option value="{{ $category->id }}">@lang('category.' . $category->name)</option>
						@endif
					@endforeach
				</select>
				<input id="searchBox" type="text" placeholder="@lang('index.searchPlaceholder')" @if($search !== null) value="{{ $search }}" @endif/>
				<button class="btn searchButton" onclick="window.location='{{ route('indexSearch', [$portal, 0, $categoryId]) }}/' + document.querySelector('#searchBox').value">@lang('index.searchSubmit')</button>
			</div>
			<div class="card-panel">
				<span class="cardTitle">@lang('index.feedersTitle')</span>
				<br>
				@foreach($feeders as $feeder)
					<p><a href="{{ route('indexFeeder', [$portal, 0, $feeder->id]) }}">{{ $feeder->name }}</a></p>
				@endforeach
			</div>
		</div>
		<div class="col l3 hide-on-med-and-down">
			<div class="card-panel">
				<span class="cardTitle">@lang('index.description_title')</span>
				<p>@lang('index.description_content')</p>
			</div>
			<div class="card-panel">
				<span class="cardTitle">@lang('index.newest_articles_title')</span>
				@foreach($latestItems as $item)
					<p><b class="bizzblue-text"><a href="{{ $item->link }}" target="_blank">{{ $item->title }}</a></b></p>
					<p>{{ $item->description }}</p>
					<span class="left">{{ $item->feedName }}</span><span class="right">{{ \Carbon\Carbon::parse($item->pubdate)->toDateString() }}</span>
					<br>
					<hr>
				@endforeach
			</div>
		</div>
	</div>
@endsection