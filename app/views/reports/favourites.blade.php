@extends('layouts.main')

@section('title')
Analytics Dashboard
@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=5.9') }}
	{{ HTML::style('_css/cso_custom.css?version=5.9') }}
	{{ HTML::style('_css/pikaday.css') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/pikaday.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/dashboard.js?version=6.1') }}
	{{ HTML::script('_js/pid.js?version=5.8') }}
	{{ HTML::script('_js/favourite_reports.js?version=5.9') }}
	{{ HTML::script('_js/tile-list.js?version=5.8') }}
@stop

@section('body')
	<div id="cso">
		<div id="main" class="{{{ isset($sub) ? 'sub' : '' }}}">
			<div class="favourites_group">
				<div class="category">
						<div class="image">
							{{HTML::image('_img/tiles/favourite.png')}}
						</div>
						<span class="name">Favourite Reports</span>
				</div>
				<ul id="tile-list">
							@foreach($favourites as $row)
							<li class="tile-li">
								<div class="favourite-button added" data-favname="{{$row['title']}}"></div>
								<a href="{{URL::to('/')}}{{$row['url']}}/r/{{$row['key']}}/{{$row['id']}}" class="title">{{$row['title']}}</a>
								<p>{{$row['description']}}</p>
							</li>
						@endforeach

				</ul>
			</div>
		</div>
	</div>
@stop
