@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s4">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('feeds') }}"><i class="material-icons">arrow_back</i></a>
				<img src="{{ asset('image/bizzfeedlogo.png') }}" style="max-width: 100%"/>
				<hr>
				<p style="font-weight: bold">Werkwijze</p>
				<p>Selecteer eventueel een portal, een feed en pas daarna de instellingen van de feed aan. Als u klaar bent klikt op op de knop "Instellingen toepassen".</p>
				<p>U kan hierna rechts het resultaat zien. Zodra alles naar wens is plaatst u de html-code (onderaan deze pagina) op uw eigen website.</p>
				<p><span style="font-weight: bold">Let op: </span>Wanneer u bij bepaalde feeds het volledige artikel op uw website wil weergeven moet u een Mybizzmail account-token invoeren.</p>
				<hr>
				<form>
					<div class="input-field">
						<select id="portalSelector" class="excludeSelect"></select>
						<label for="portalSelector">@lang('bizzfeed.creator.portalSelector')</label>
					</div>
					<div class="input-field">
						<select id="feedSelector" class="excludeSelect"></select>
						<label for="feedSelector">@lang('bizzfeed.creator.feedSelector')</label>
					</div>
					<div class="input-field" id="memberBox" style="display: none;">
						<label>
							<input type="checkbox" class="filled-in" id="articleFull"/>
							<span>@lang('bizzfeed.creator.articleFull')</span>
						</label>
						<br><br>
					</div>
					<div class="input-field" id="tokenBox" style="display: none;">
						<input type="text" id="tokenField" autocomplete="off"/>
						<label for="tokenField">@lang('bizzfeed.creator.tokenField')</label>
						<button type="button" class="btn" onclick="activateToken()">@lang('bizzfeed.creator.activateToken')</button>
					</div>
					<div class="input-field">
						<input type="number" min="1" max="25" value="10" id="itemCount"/>
						<label for="itemCount">@lang('bizzfeed.creator.itemCount')</label>
					</div>
					<div class="input-field">
						<input type="number" min="100" max="9999" value="200" id="widthCount"/>
						<label for="widthCount">@lang('bizzfeed.creator.widthCount')</label>
					</div>
					<div class="input-field">
						<input type="number" min="0" max="45" value="5" id="cornerRadiusCount"/>
						<label for="cornerRadiusCount">@lang('bizzfeed.creator.cornerRadiusCount')</label>
					</div>
					<div class="input-field">
						<label>
							<input type="checkbox" class="filled-in" id="shadowBool"/>
							<span>@lang('bizzfeed.creator.shadowBool')</span>
						</label>
					</div>
					<br><br>
					<table class="bizzFeedCreatorTable">
						<tbody>
							<tr>
								<td class="bizzFeedCreatorFirst">
									<strong>@lang('bizzfeed.creator.colors')</strong>
								</td>
								<td>@lang('bizzfeed.creator.colors.background')</td>
								<td>@lang('bizzfeed.creator.colors.foreground')</td>
							</tr>
							<tr>
								<td class="bizzFeedCreatorFirst">@lang('bizzfeed.creator.colors.titlebar')</td>
								<td class="bizzFeedCreatorColorBox"><input type="text" id="titlebarColor"/></td>
								<td class="bizzFeedCreatorColorBox"><input type="text" id="titleColor"/></td>
							</tr>
							<tr>
								<td class="bizzFeedCreatorFirst">@lang('bizzfeed.creator.colors.messages')</td>
								<td class="bizzFeedCreatorColorBox"><input type="text" id="messagebarColor"/></td>
								<td class="bizzFeedCreatorColorBox"><input type="text" id="messageColor"/></td>
							</tr>
						</tbody>
					</table>
					<div class="input-field">
						<select id="fontFamily" class="form-control">
							<option value="Georgia">Georgia</option>
							<option value="Palatino">Palatino</option>
							<option value="Times">Times</option>
							<option value="serif">Serif</option>
							<option value="Arial">Arial</option>
							<option value="Gadget">Arial black</option>
							<option value="Cursive">Comic Sans</option>
							<option value="Impact">Charcoal</option>
							<option value="sans-serif">Sans-serif</option>
							<option value="Tahoma">Tahoma</option>
							<option value="Helvetica">Helvetica</option>
							<option value="Verdana">Verdana</option>
							<option value="Courier">Courier</option>
							<option value="Monaco">Monaco</option>
							<option value="monospace">Monospace</option>
						</select>
						<label for="fontFamily">@lang('bizzfeed.creator.fontFamily')</label>
					</div>
					<div class="input-field">
						<input type="number" id="fontSize" min="8" max="32" value="10"/>
						<label for="fontSize">@lang('bizzfeed.creator.fontSize')</label>
					</div>
					<div class="center-align">
						<button class="btn orange" type="button" onclick="reload()">@lang('bizzfeed.creator.reload')</button>
					</div>
					<br>
					<div class="center-align">
						<button class="btn" type="button" onclick="copyToClipboard(document.getElementById('htmlCode'))">@lang('bizzfeed.creator.copyClipboard')</button>
					</div>
					<br>
					<div class="grey darken-3 white-text" id="htmlCode" style="overflow-wrap: break-word; padding: 5px;"></div>
				</form>
			</div>
		</div>
		<div class="col s8">
			<div class="previewPanel" id="previewPanel">
				<script id="bizz" src="{{ asset('js/bizzfeed.js') }}"></script>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="{{ asset('js/colorpicker.js') }}"></script>
	<link href="{{ asset('css/colorpicker.css') }}" rel="stylesheet">
	<script src="{{ asset('js/bizzfeedcreator.js') }}"></script>
	<link href="{{ asset('css/bizzfeed.css') }}" rel="stylesheet">
@endsection