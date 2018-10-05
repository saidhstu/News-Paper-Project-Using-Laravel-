@extends('layouts.search')
@section('pagination')
	<ul class="pagination">
		<li class="{{ $paginateCurrent === 0 ? 'disabled' : 'waves-effect' }}">
			<a href="{{ $paginateCurrent === 0 ? 'javascript:void(0)' : route('indexSearch', [$portal, $paginateCurrent - 1, $categoryId, $search ? $search : '']) }}"><i class="material-icons">chevron_left</i></a>
		</li>
		@for($i = $paginateMin; $i < $paginateMax; ++$i)
			<li class="{{ $i === $paginateCurrent ? 'disabled active' : 'waves-effect' }}">
				<a href="{{ $i === $paginateCurrent ? 'javascript:void(0)' : route('indexSearch', [$portal, $i, $categoryId, $search ? $search : '']) }}">{{ $i + 1}}</a>
			</li>
		@endfor
		<li class="{{ $paginateCurrent === ($paginateMax - 1) ? 'disabled' : 'waves-effect' }}">
			<a href="{{ $paginateCurrent === ($paginateMax - 1) ? 'javascript:void(0)' : route('indexSearch', [$portal, $paginateCurrent + 1, $categoryId, ($search ? $search : '')]) }}"><i class="material-icons">chevron_right</i></a>
		</li>
	</ul>
@endsection