<nav>
	<ul>
		<li  id="first_project" class="@if($project == 'roadmap') active @endif">
			<a href="{{URL::to('/')}}/product/roadmap">Roadmap</a>
		</li>
		<li class="@if($project == 'requests') active @endif">
			<a href="{{URL::to('/')}}/product/requests">Requests</a>
		</li>
	</ul>
</nav>
