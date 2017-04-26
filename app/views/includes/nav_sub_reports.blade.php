<ul class="sidebar-sub sidebar_analytics-sub">
	@foreach (NavigationMenuReport::mainPage() as $main)
		<li>
			@if (empty($main['sub']) == [])
				<span class="">{{$main['name']}}</span>
			@else
				<span><a class="" href="<?=URL::to($main['url']); ?>">{{$main['name']}}</a></span>
			@endif
			<ul>
				@foreach ($main['sub'] as $category)
					<li>
						@if (empty($category['sub']) == [])
							<span>{{$category['name']}}</span>
						@else
							<a href="<?=URL::to($category['url']); ?>">{{$category['name']}}</a>
						@endif

						<ul class="channel">
							@foreach ($category['sub'] as $sub)
								<li class="channel_{{str_replace(' ', '', $sub['name'])}}">
									<a href="<?=URL::to($sub['url']); ?>">- {{$sub['name']}}</a>
								</li>
							@endforeach
						</ul>
					</li>
				@endforeach
			</ul>
		</li>
	@endforeach
	<li>
		<span><a class="" href="{{route('favourites.reports')}}">Favourite Reports</a></span>
	</li>
	<li>
		<form method="get" action="<?=URL::to('/analytics/search');?>">
			<input type="text" name="search" class="search" placeholder="Search Reports" />
		</form>
	</li>
</ul>