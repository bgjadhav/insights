@extends('reports.main')

@section('main')
	<div id="tiles" class="search_results">
		<form method="get" action="<?=URL::to('/analytics/search');?>">
			<input type="text" name="search" class="search_box" value="{{Input::get('search')}}" />
		</form>

		@if (!empty($results))
			<h1>Search Results for: <span>{{Input::get('search')}}</span></h1>

			@foreach($results as $category)
				<div class="search_group">
					<div class="category">
						<div class="image">
							{{HTML::image('_img/tiles/' . $category['icon'])}}
						</div>
						<span class="name">{{$category['name']}}</span>
					</div>
					<ul id="tile-list">
						@foreach($category['results'] as $row)
							<li class="tile-li">
								<div class="favourite-button @if(in_array($row['title'], $favourites)) added @endif" data-favname="{{$row['title']}}"></div>
								<a href="{{URL::to('/')}}{{$category['url']}}/r/{{$row['key']}}/{{$row['id']}}" class="title">{{$row['title']}}</a>
								<p>{{$row['description']}}</p>
							</li>
						@endforeach
					</ul>
				</div>
			@endforeach
		@else
			<h1>There are no search results matching your criteria.</h1>
		@endif

	</div>
@stop
