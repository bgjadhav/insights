<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse !important;">

	@include('jira.product.roadmap.email.tableHead')

	@foreach ($data as $ticket)

		<?php

			$ticket = RoadmapWeeklyMailingCommand::helperRegion($ticket);

			if (!empty($ticket['historial'])) {

				$total = $ticket['historial'];

				$ticket['historial'] =  array_reverse($ticket['historial']);

				if (isset($ticket['historial']['project'])) {
					$first_key = 'project';
					unset($total[$first_key]);

				} elseif (isset($ticket['historial']['new'])) {
					$first_key = 'new';
					unset($total[$first_key]);

				} else {
					$first_key = key($ticket['historial']);
				}

				$item  = [
					'label' => $labels[$first_key],
					'changes' => $ticket['historial'][$first_key]['changes']
				];

				unset($ticket['historial'][$first_key]);

				$last_one = key($total);
			}

			$background_category = 'background:'
				.RoadmapWeeklyMailingCommand::backgroundColorToCategoryInHexa($ticket['first_component'])
				.';';

		?>


		@include('jira.product.roadmap.email.tableItemSecondRow')


		@include('jira.product.roadmap.email.tableItemThirdOrMoreRow')


	@endforeach

</table>
