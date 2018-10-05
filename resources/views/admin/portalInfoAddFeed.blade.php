@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('adminPortalInfo', $portalId) }}"><i class="material-icons">arrow_back</i></a>
				<form method="POST" action="{{ route('adminPortalInfoAddFeedSubmit', $portalId) }}">
					{{ csrf_field() }}
					<div class="center-align">
						<button type="submit" class="btn">@lang('admin.portal.info.addFeeds.submit')</button>
					</div>
					<br>
					<br>
					<table class="highlight">
						<tbody>
							@foreach($feeds as $feed)
								<tr>
									<td class="valign-wrapper">
										<label>
											<input type="checkbox" name="addFeeds[{{ $feed->id }}]" class="filled-in"/>
											<span class="black-text">{{ $feed->name }}</span>
										</label>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<br>
					<div class="center-align">
						<button type="submit" class="btn">@lang('admin.portal.info.addFeeds.submit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection