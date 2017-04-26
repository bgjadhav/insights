@extends('reports.main')

@section('main')
	<div id="tiles">
		<div class="category">
			<div class="image">
				{{HTML::image('_img/tiles/' . $category['icon'])}}
			</div>
			<span class="name">{{$category['name']}}</span>
		</div>
		<ul id="tile-list">
			@foreach ($category['report'] as $key => $report)
				<li id="tile-li-{{$key}}" class="tile-li">
					<div class="favourite-button @if(in_array($report['title'], $favourites)) added @endif" data-favname="{{$report['title']}}">
					</div>
					<a href="{{URL::to('/')}}{{$category['url']}}/r/{{$key}}/{{$report['id']}}" class="title">{{$report['title']}}</a>
					<p>{{$report['description']}}</p>
				</li>
			@endforeach
		</ul>
	</div>
	<script>
		$(function() {
			var pid = new DashboardPID("{{$ulrLevel}}");
			pid.init();
			var tileList = new TileList();
			tileList.init();
		})
	</script>
@stop
