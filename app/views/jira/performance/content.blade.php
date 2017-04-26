@foreach ($sections['display'] as $section)
<div id="{{$section['class']}}" class="loading">
	{{HTML::image('_img/loading.gif')}}
</div>
@endforeach


@include('jira.performance.templates')

<script>
	$(function() {
		var analytics = [];
		@foreach ($sections['display'] as $key => $section)
			analytics["{{$key}}"] = new Dashboard("{{$section['class']}}", "{{$ulrLevel}}", "{{$section['title']}}");
			analytics["{{$key}}"].init();
		@endforeach
	});
</script>
