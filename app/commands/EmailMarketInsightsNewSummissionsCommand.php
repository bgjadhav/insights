<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailMarketInsightsNewSummissionsCommand extends Command {

	protected $name	= 'email:sent:approved:INTL';
	protected $description = 'CLI that send email with all INTL tickets that have an Approved status, only one time.';
	protected $issues_id = [];
	protected $test = [];


	/**
	 * Execute the console command.
	 *
	 */
	public function fire()
	{



		$this->sendEmail($this->prepareDataAndGetItForEmail());

		// send email

		// update id

		//~ $this->info(print_r($this->prepareDataAndGetItForEmail()));
		//~ die;

		//~ Mail::send(
			//~ 'jira.intl.email',
			//~ [
				//~ 'data'		=> $data,
				//~ 'config'	=> $config,
				//~ 'logo'		=> 'logo.png',
				//~ 'widthLogo'	=> '140',
				//~ 'heightLogo'=> '40'
			//~ ],
			//~ function($message) use($config) {
				//~ $message->to('koruequiroz@mediamath.com', 'Product Operations');
			//~
				//~ $message->from('insights_noreply@mediamath.com', 'Insights')
					//~ //->cc('open_analytics@mediamath.com', 'OPEN Analytics')
					//~ ->subject($config['title'].'Pipeline');
			//~ }
		//~ );
	}


	private function prepareDataAndGetItForEmail()
	{
		$data = $this->data();

		array_walk ($data, function(&$item) {
			$item = $this->formatItemAndStoreId($item);

		});

		return $data;
	}

	private function data()
	{
		return QueryService::run(
			'SELECT t.issue_id, t.`key`, t.summary, t.date_created, t.region, t.creator, mc.companies, ml.labels'
			.' FROM issues t'
			.' LEFT JOIN ('
					.'SELECT c.issue_id, GROUP_CONCAT(c.component separator \', \') AS companies'
					.' FROM meta_component c'
					.' GROUP BY c.issue_id'
					.' ORDER BY c.issue_id, c.component'
				.') mc ON mc.issue_id = t.issue_id'
			.' LEFT JOIN ('
					.'SELECT la.issue_id, GROUP_CONCAT(la.label separator \', \') AS labels'
					.' FROM meta_labels la'
					.' GROUP BY la.issue_id'
					.' ORDER BY la.issue_id, la.label'
				.') ml ON ml.issue_id = t.issue_id'
			.' WHERE t.status = "Approved"'
				.' AND t.validate = 1'
				.' AND t.issue_id NOT IN'
				.' (SELECT s.issue_id FROM sent_new_summission s)'
			.' ORDER BY t.date_created',

			'jira_intel',

			false
		);
	}

	private function formatItemAndStoreId($item)
	{
		$this->issues_id[$item->issue_id] = $item->issue_id;
		$item->url = 'http://issues.mediamath.com/browse/'.$item->key;

		$item->region = trim($item->region);
		$item->companies = trim($item->companies);
		$item->labels = trim($item->labels);

		if ($item->region == '') {
			$item->region = 'GLOBAL';
		}

		if ($item->companies == '') {
			$item->companies = '---';
		}

		if ($item->labels == '') {
			$item->labels = 'No Labels';
		}
		return $item;
	}


	private function sendEmail($data)
	{
		// @todo If there are not data send another template

		Mail::send(
			'jira.marketinsights.email',

			[
				'data' => $data,
				'logo' => 'logo.png',
				'widthLogo' => '140',
				'heightLogo' => '40'
			],

			function ($message) {
				$message->to('koruequiroz@mediamath.com', 'Product Operations');
				$message->from('insights_noreply@mediamath.com', 'Insights')
					->cc('PrOps@mediamath.com', 'Product Operations')
					->subject('Weekly Market Insights Subscription');
			}
		);
	}


	protected function getArguments()
	{
		return [];
	}

	protected function getOptions()
	{
		return [];
	}

}
