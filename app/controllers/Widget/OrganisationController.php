<?php
class OrganisationController extends Controller
{
	public function update_organisations()
	{
		return View::make('widgets.upload')->with('type', 'upload');
	}

	public function update_organisations_post()
	{
		Excel::selectSheets('Master Orgs')->load(Input::file('file'), function($reader) {
			$reader = $reader->toArray();
			$all = array(
				'table' => true,
				'data' => array()
			);

			foreach($reader as $row) {
				$row = array_change_key_case($row, CASE_UPPER);
				array_push($all['data'], $row);
				$data = array();
				foreach($row as $key => $column) {
					if($key != '0') {
						$data[$key] = $column;
					}
				}

				if(Input::get('save')) {
					try {
						// recursive database functions :(
						$query = DB::reconnect('warroom_write')
						->table('META_ORGS')
						->select('ORG_ID', 'ORG_NAME', 'MASTER_ORG')
						->where('ORG_ID', $data['ORG_ID'])
						->get();
						if(count($query) > 0) {
							DB::reconnect('warroom_write')
							->table('META_ORGS')
							->where('ORG_ID', $data['ORG_ID'])
							->update(
								array(
									'ORG_ID' => $data['ORG_ID'],
									'ORG_NAME' => $data['ORG_NAME'],
									'MASTER_ORG' => $data['MASTER_ORG_ID'],
									'ORG_ID' => $data['ORG_ID']
								)
							);
						} else {
							// insert
							DB::reconnect('warroom_write')
							->table('META_ORGS')->insert(
								array(
									'ORG_ID' => $data['ORG_ID'],
									'ORG_NAME' => $data['ORG_NAME'],
									'MASTER_ORG' => $data['MASTER_ORG_ID'],
									'ORG_ID' => $data['ORG_ID']
								)
							);
						}
					} catch(Exception $e) {
						echo $e;
						die();
					}
				}
			}

			if(Input::get('save')) {
				$all['table'] = false;
				$all['data'] = false;
				$all['message'] = 'saved';
			}

			echo json_encode($all);
			die();
		});
	}

	public function getPodOrg()
	{
		try {
			return Filter::getPodsOrganization(Input::get('pods'));
		} catch (Exception $e) {
			ErrorService::standard($e->getMessage());
		}
	}
}
?>
