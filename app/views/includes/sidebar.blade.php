<aside id="sidebar">

	<nav id="nav-main">
		<ul>
			<a class="nav-link" href="<?=URL::to('/product/roadmap') ?>">
				<li class="roadmap toLink"><span>Roadmap</span></li>
			</a>
			<a class="nav-link" href="<?=URL::to('/market_insights') ?>">
				<li class="market toLink"><span>Market Insights</span></li>
			</a>
			<a class="nav-link" href="<?=URL::to('/analytics/jira/performance-metrics/r/0/product-jira-performance?load=1') ?>">
				<li class="performance toLink"><span>User Stats</span></li>
			</a>

			<li class="sidebar_analytics"><span>Reports</span></li>

			@if(User::hasRole(['PublisherSolution']))
				<li class="sidebar_knowledge"><span>Knowledge</span></li>
			@endif

		</ul>
	</nav>

	<nav id="nav-sub">
		<span class="triangle"></span>

		@include('includes.nav_sub_reports')

		@if(User::hasRole(['PublisherSolution']))
			@include('includes.nav_sub_knowledge')
		@endif
	</nav>

</aside>
