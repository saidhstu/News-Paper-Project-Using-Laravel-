@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="{{ route('feedsAdd') }}"><i class="material-icons">arrow_back</i></a>
		<form method="POST" action="{{ route('feedsAddParsedSubmit') }}">
			{{ csrf_field() }}
			<div class="center-align bizzblue-text">
				<h4>{{ $rss }}</h4>
				<input type="hidden" name="rss" value="{{ str_replace('/', '_', base64_encode($rss)) }}"/>
			</div>
			<hr>
			<h5>@lang('feeds.add.details')</h5>
			<div class="input-field">
				<i class="material-icons prefix">rss_feed</i>
				<input type="text" id="title" name="title" value="{{ old('title') }}" required="required" autofocus="autofocus"/>
				<label for="title">@lang('feeds.add.title')</label>
				@if($errors->has('title'))
					<span class="helper-text red-text">{{ $errors->first('title') }}</span>
				@endif
			</div>
			<div class="input-field">
				<i class="material-icons prefix">message</i>
				<input type="text" id="description" name="description" value="{{ old('description') }}" required="required"/>
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
							<option value="{{ $category->id }}">@lang('category.' . $category->name)</option>
						@endforeach
					</select>
				</div>
			</div>
			@if(\App\Utils\Utils::isUserAdmin())
				<div class="row">
					<div class="col s12 m4">
						<strong>@lang('feeds.add.public')</strong>
					</div>
					<div class="col s12 m8">
						<label>
							<input id="public" class="filled-in" type="checkbox" name="public" {{ old('public') ? 'checked="checked"' : '' }} checked/>
							<span></span>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 m4">
						<strong>@lang('feeds.add.ownerBox')</strong>
					</div>
					<div class="col s12 m8">
						<select name="owner">
							@foreach($users as $user)
								@if ($user->id == \Illuminate\Support\Facades\Auth::id())
									<option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
								@else
									<option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
				<br>
				<br>
			@endif
			<hr>
			<h5>@lang('feeds.add.foundFields')</h5>
			<table class="highlight">
				<thead>
					<tr>
						<td>@lang('feeds.add.path')</td>
						<td>@lang('feeds.add.currentValue')</td>
						<td>@lang('feeds.add.keytype')</td>
						{{--<td>@lang('feeds.add.filtertype')</td>--}}
					</tr>
				</thead>
				<tbody>
					@foreach($items as $item)
						<tr>
							@if(in_array(str_replace('[item]', $itempath, $item['path']), $specpaths))
								<td>{{ $item['path'] }} <b>(@lang('feeds.add.isSpec'))</b></td>
							@else
								<td>{{ $item['path'] }}</td>
							@endif
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
										@if($keytype->name == 'ignore' && in_array(str_replace('[item]', $itempath, $item['path']), $specpaths))
											<option value="{{$keytype->id}}">@lang('keytype.automatic')</option>
										@else
											<option value="{{$keytype->id}}">@lang('keytype.' . $keytype->name)</option>
										@endif
									@endforeach
								</select>
							</td>
							<td class="addFeedSelect" style="display: none;">
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
				<button type="submit" class="btn">@lang('feeds.add.submit')</button>
			</div>
		</form>
	</div>
@endsection