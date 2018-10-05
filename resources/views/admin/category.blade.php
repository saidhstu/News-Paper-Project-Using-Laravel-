@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="{{ route('admin') }}"><i class="material-icons">arrow_back</i></a>
		<form method="POST" action="{{ route('adminCategorySubmit') }}">
			<div class="center-align">
				<button class="btn" type="button" onclick="window.location='{{ route('adminCategoryAdd') }}'">@lang('admin.category.add')</button>
				<button class="btn" type="submit">@lang('admin.category.submit')</button>
			</div>
			<br>
			{{ csrf_field() }}
			<table class="highlight">
				<thead>
					<tr>
						<td>@lang('admin.category.name')</td>
						<td>@lang('admin.category.type')</td>
						<td>@lang('admin.category.parent')</td>
					</tr>
				</thead>
				<tbody>
					@foreach($rootCategories as $category)
						<tr>
							<td><input type="text" name="name[cat_{{ $category->id }}]" value="{{ $category->name }}"/></td>
							<td>@lang('admin.category.rootCategory')</td>
							<td>
								<select name="parentCategory[cat_{{ $category->id }}]">
									<option value="-1" selected="selected">@lang('admin.category.none')</option>
									@foreach($rootCategories as $rootCategory)
										<option value="{{ $rootCategory->id }}">{{ $rootCategory->name }}</option>
									@endforeach
								</select>
							</td>
							<td class="center-align"><a href="{{ route('adminCategoryInfo', $category->id) }}"><i class="material-icons blue-text">info</i></a></td>
						</tr>
					@endforeach
					@foreach($categories as $category)
						<tr>
							<td><input type="text" name="name[cat_{{ $category->id }}]" value="{{ $category->name }}"/></td>
							<td>@lang('admin.category.subCategory')</td>
							<td>
								<select name="parentCategory[cat_{{ $category->id }}]">
									<option value="-1">@lang('admin.category.none')</option>
									@foreach($rootCategories as $rootCategory)
										@if($category->parent_id === $rootCategory->id)
											<option value="{{ $rootCategory->id }}" selected="selected">@lang($rootCategory->name)</option>
										@else
											<option value="{{ $rootCategory->id }}">{{ $rootCategory->name }}</option>
										@endif
									@endforeach
								</select>
							</td>
							<td class="center-align"><a href="{{  route('adminCategoryInfo', $category->id) }}"><i class="material-icons blue-text">info</i></a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<br>
			<div class="center-align">
				<button class="btn" type="button" onclick="window.location='{{ route('adminCategoryAdd') }}'">@lang('admin.category.add')</button>
				<button class="btn" type="submit">@lang('admin.category.submit')</button>
			</div>
			<br>
		</form>
	</div>
@endsection