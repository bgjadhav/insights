<nav>
	<ul>
		<li class="@if($project == 'product') active @endif">
			<a href="{{URL::to('/')}}/analytics/jira/performance-metrics/r/0/product-jira-performance">PRDREQ SLAs</a>
		</li>

		@if (User::isKathia())
		<li class="@if($project == 'stats') active @endif">
			<a href="{{URL::to('/')}}/roadmap/stats">Roadmap Usage</a>
		</li>
		@endif
	</ul>
</nav>
