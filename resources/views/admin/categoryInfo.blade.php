@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('adminCategory') }}"><i class="material-icons">arrow_back</i></a>
				<h5 class="bizzblue-text">@lang('admin.category.info.name')</h5>
				<p>{{ $category->name }}</p>
				<h5 class="bizzblue-text">@lang('admin.category.info.id')</h5>
				<p>{{ $category->id }}</p>
				@if ($category->parent_id !== NULL)
					<h5 class="bizzblue-text">@lang('admin.category.info.parent')</h5>
					<p>{{ $category->parent->name }}</p>
				@endif
				<div class="center-align">
					<a class="btn red waves-effect waves-light modal-trigger" href="#deleteModal">@lang('admin.category.info.delete')</a>
					<div id="deleteModal" class="modal red darken-3">
						<div class="modal-content white-text">
							<h4>@lang('admin.category.info.deleteConfirmQuestion')</h4>
						</div>
						<div class="modal-footer red darken-3">
							<a href="{{ route('adminCategoryDelete', $category->id) }}" class="white-text modal-action modal-close btn-flat">@lang('admin.category.info.deleteSubmit')</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection