@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="javascript:history.back()"><i class="material-icons">arrow_back</i></a>
		<form method="POST" action="{{ route('feedsModifySubmit', $feed->id) }}">
			{{ csrf_field() }}
			<div class="center-align bizzblue-text">
				<h4>{{ $rss }}</h4>
			</div>
			<hr>
			<h5>@lang('feeds.add.details')</h5>
			<div class="input-field">
				<i class="material-icons prefix">rss_feed</i>
				<input type="text" id="title" name="title" required="required" autofocus="autofocus" value="{{ $feed->name }}"/>
				<label for="title">@lang('feeds.add.title')</label>
				@if($errors->has('title'))
					<span class="helper-text red-text">{{ $errors->first('title') }}</span>
				@endif
			</div>
			<div class="input-field">
				<i class="material-icons prefix">message</i>
				<input type="text" id="description" name="description" required="required" value="{{ $feed->description }}"/>
				<label for="description">@lang('feeds.add.description')</label>
				@if($errors->has('description'))
					<span class="helper-text red-text">{{ $errors->first('description') }}</span>
				@endif
			</div>
			<div class="row">
				<div class="col s12 m4">
					<strong>@lang('feeds.add.category')</strong>
				</div>
				<div class="col s12 m8">
					<select name="category">
						@foreach($categories as $category)
							@if ($feed->category_id == $category->id)
								<option value="{{ $category->id }}" selected="selected">@lang('category.' . $category->name)</option>
							@else
								<option value="{{ $category->id }}">@lang('category.' . $category->name)</option>
							@endif
						@endforeach
					</select>
				</div>
			</div>
			<hr>
			<h5>@lang('feeds.modify.oldFields')</h5>
			<table class="highlight">
				<thead>
					<tr>
						<td>@lang('feeds.add.path')</td>
						<td>@lang('feeds.add.keytype')</td>
						<td>@lang('feeds.add.filtertype')</td>
					</tr>
				</thead>
				<tbody>
					@foreach($currentMappings as $current)
						<tr>
							<td>{{ $current->path }}</td>
							<td class="addFeedSelect">
								<select name="currentKeytype[{{ $current->path }}]">
									@foreach($keytypes as $keytype)
										@if ($current->keytype_id == $keytype->id)
											<option value="{{$keytype->id}}" selected="selected">@lang('keytype.' . $keytype->name)</option>
										@else
											<option value="{{$keytype->id}}">@lang('keytype.' . $keytype->name)</option>
										@endif
									@endforeach
								</select>
							</td>
							<td class="addFeedSelect">
								<select name="currentFiltertype[{{ $current->path }}]">
									@foreach($filtertypes as $filtertype)
										@if($current->filtertype_id == $filtertype->id)
											<option value="{{$filtertype->id}}" selected="selected">@lang('filtertype.' . $filtertype->name)</option>
										@else
											<option value="{{$filtertype->id}}">@lang('filtertype.' . $filtertype->name)</option>
										@endif
									@endforeach
								</select>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<hr>
			<h5>@lang('feeds.add.foundFields')</h5>
			<table class="highlight">
				<thead>
					<tr>
						<td>@lang('feeds.add.path')</td>
						<td>@lang('feeds.add.currentValue')</td>
						<td>@lang('feeds.add.keytype')</td>
						<td>@lang('feeds.add.filtertype')</td>
					</tr>
				</thead>
				<tbody>
					@foreach($items as $item)
						<tr>
							<td>{{ $item['path'] }}</td>
							<td>
								@if($item['isAttribute'])
									<b>(@lang('feeds.add.attribute'))</b>
									<input type="hidden" name="attributes[{{ $item['path']}}]" value="true"/>
								@endif
								{{ $item['display'] }}
							</td>
							<td class="addFeedSelect">
								<select name="keytype[{{ $item['path'] }}]">
									@foreach($keytypes as $keytype)
										<option value="{{$keytype->id}}">@lang('keytype.' . $keytype->name)</option>
									@endforeach
								</select>
							</td>
							<td class="addFeedSelect">
								<select name="filtertype[{{ $item['path'] }}]">
									@foreach($filtertypes as $filtertype)
										<option value="{{$filtertype->id}}">@lang('filtertype.' . $filtertype->name)</option>
									@endforeach
								</select>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="input-field center-align">
				<button type="submit" class="btn">@lang('feeds.modify.submit')</button>
			</div>
		</form>
	</div>
@endsection