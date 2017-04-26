@extends('reports.main')

@section('main')
	<div id="categories">
		@foreach($categories['sub'] as $key => $category)
			<a class="category" href="{{$category['url']}}">
				<div class="image">
					{{HTML::image('_img/tiles/' . $category['icon'])}}
				</div>
				<span class="name">{{$category['name']}}</span>
			</a>
		@endforeach
	</div>
	<script>
		$(function() {
			var pid = new DashboardPID("{{$ulrLevel}}");
			pid.init();
		})
	</script>
@stop
