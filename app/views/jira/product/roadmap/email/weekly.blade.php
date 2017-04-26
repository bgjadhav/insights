@extends('jira.product.roadmap.email.mailing')

@section('content')

	<?php if (!empty($data)) { ?>

        @include('jira.product.roadmap.email.note')

		@include('jira.product.roadmap.email.table')

	<?php } else { ?>
		@include('jira.product.roadmap.email.noData')

	<?php } ?>

	@include('jira.product.roadmap.email.footer')

@stop
