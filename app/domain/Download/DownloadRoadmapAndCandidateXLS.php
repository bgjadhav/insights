<?php
use Illuminate\Filesystem\Filesystem;

class DownloadRoadmapAndCandidateXLS implements DownloadInterface
{
	public static function output(&$out, $config)
	{
		Excel::create('tmp__'.$config['file'], function($excel) use($config) {

			$pdo = DB::reconnect($config['conn'])->getPdo();

			$query = $pdo->prepare($config['query']);


			$excel->sheet('Instructions', function($sheet) use($config) {

				$sheet->setFontFamily('Arial');

				$sheet->cells('A2', function($cell) {

						$cell->setValue('Please click here for Best Practices');
						$cell->setAlignment('left');
						$cell->setValignment('middle');

						$cell->setFont([
							'family' => 'Arial',
							'size' => '11',
							'underline' => 'single',
							'color' => array('rgb' => '0000FF')
						]);
				});

				$sheet->setSize('A2', 30, 14);
				$sheet->setSize('A1', 30, 14);

				$sheet->getCell('A2')
					->getHyperlink()
					->setUrl('https://wiki.mediamath.com/login.action?os_destination=%2Fpages%2Fviewpage.action%3FpageId%3D329163358&permissionViolation=true#ProductRoadmap,Candidates,andRequests-wheredoIfindthem?-RoadmapandCandidateExportDisplay');

				$sheet->mergeCells('A2:Z2');


				$sheet->cells('A4', function($cell) {
					$cell->setValue('An overview of the Roadmap export in Excel format');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->setSize('A4', 30, 14);

				$sheet->mergeCells('A3:Z3');
				$sheet->mergeCells('A4:Z4');


				$sheet->cells('A6', function($cell) {
					$cell->setValue('TAB 1: Roadmap');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B6', function($cell) use($config) {
					$cell->setValue('('.$config['applied']['roadmap'].')');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->setSize('A6', 30, 14);

				$sheet->mergeCells('A5:Z5');
				$sheet->mergeCells('B6:Z6');


				$sheet->cells('A7', function($cell) {
					$cell->setValue('1. PRODUCT CATEGORIES: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B7', function($cell) {
					$cell->setValue('Product Categories are displayed vertically in the first column with the applicable tickets in rows in subsequent columns.');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A7', 30, 14);
				$sheet->mergeCells('B7:Z7');


				$sheet->cells('A8', function($cell) {
					$cell->setValue('2. QUARTERS: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});


				$sheet->cells('B8', function($cell) {
					$cell->setValue('When the Full Roadmap is exported all quarters for the current defaulted year plus the next (3) quarters will be displayed. If the project has no quarter denoted for any of its phases it will appear in the "None" column.');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A8', 30, 14);
				$sheet->mergeCells('B8:Z8');


				$sheet->cells('A9', function($cell) {
					$cell->setValue('3. PROJECT NAME: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B9', function($cell) {
					$cell->setValue('The project name hyperlinks to underlying Product Requirements Document (PRD) located in JIRA with full field set and more detailed information.');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A9', 30, 14);
				$sheet->mergeCells('B9:Z9');


				$sheet->cells('A10', function($cell) {
					$cell->setValue('4. RELEASE PHASE: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B10', function($cell) {
					$cell->setValue('The release phase is noted next to the project name for a given estimate (e.g., ProjectX (Open Beta)). In the event where multiple release phase estimates in multiple quarters have been set, the project will appear in all applicable quarters with appropriate release phase labels.');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A10', 30, 14);
				$sheet->mergeCells('B10:Z10');


				$sheet->cells('A11', function($cell) {
					$cell->setValue('5. RELEASED TICKETS: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B11', function($cell) {
					$cell->setValue("Any ticket moved into the 'Released' phase is highlighted in BLUE.");
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A11', 30, 14);
				$sheet->mergeCells('B11:Z11');
				$sheet->mergeCells('A12:Z12');


				$sheet->cells('A13', function($cell) {
					$cell->setValue('TAB 2: Candidates List');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B13', function($cell) use($config) {
					$cell->setValue('('.$config['applied']['candidates'].')');
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->setSize('A13', 30, 14);
				$sheet->mergeCells('B13:Z13');


				$sheet->cells('A14', function($cell) {
					$cell->setValue('1. PRODUCT CATEGORIES:');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B14', function($cell) {
					$cell->setValue("Product Categories are displayed horizontally in columns with all candidate tickets listed out in the rows below. Projects are sorted in alphabetical order, not by priority.");
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A14', 30, 14);
				$sheet->mergeCells('B14:Z14');


				$sheet->cells('A15', function($cell) {
					$cell->setValue('2. PROJECT NAME: ');
					$cell->setAlignment('right');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  true
					]);
				});

				$sheet->cells('B15', function($cell) {
					$cell->setValue("The project name hyperlinks to underlying Product Requirements Document (PRD) located in JIRA with full field set and more detailed information.");
					$cell->setAlignment('left');
					$cell->setValignment('middle');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '10',
						'color' => array('rgb' => '4d4d4d'),
						'bold' =>  false
					]);
				});

				$sheet->setSize('A15', 30, 14);
				$sheet->mergeCells('B15:Z15');

				$sheet->mergeCells('A16:Z16');
				$sheet->mergeCells('A1:Z1');

				$sheet->setAutoSize(range('A', 'B'));

			});




			$excel->sheet('Roadmap', function($sheet)  use (&$query, $config) {

				$config['quarters'] = RoadmapQuarters::filter($config);

				$none = [];

				$sheet->cells('A1', function($cell) {
					$cell->setValue('Category');
					$cell->setAlignment('center');
					$cell->setValignment('middle');
					$cell->setBackground('#4C9900');
					$cell->setFont([
						'family' => 'Arial',
						'size' => '12',
						'bold' =>  true
					]);
				});

				$sheet->setSize('A1', 100, 20);



				$quarters = [];

				$letters = range('B', 'Z');
				$index = [];
				$letter_none = '';

				foreach ($config['quarters'] as $QX) {

					foreach ($QX['belong'] as $q) {

						$letter = array_shift($letters);

						if ($q == 'None') {
							$letter_none = $letter;
						}

						$val = $q.' '.$QX['year'];

						$index[$letter] = $val;

						$sheet->cells($letter.'1', function($cell) use ($val) {
						$cell->setValue($val);
						$cell->setAlignment('center');
						$cell->setValignment('middle');
						$cell->setBackground('#4C9900');
						$cell->setFont([
							'family' => 'Arial',
							'size' => '12',
							'bold' =>  true
							]);
						});
						$sheet->setSize($letter.'1', 100, 20);
					}
				}

				$query->execute();
				$letters = range('B', 'Z');

				$categories = [];
				$column = [
					'B' => 1,
					'C'=> 1,
					'D' => 1,
					'E'=> 1,
					'F'=> 1,
					'G'=> 1,
					'H'=> 1,
					'I'=> 1,
					'J'=> 1,
					'K'=> 1,
					'L'=> 1,
					'M'=> 1,
					'N'=> 1,
					'O'=> 1,
					'P'=> 1,
					'Q'=> 1,
					'R'=> 1,
					'S'=> 1,
					'T'=> 1,
					'U'=> 1,
					'V'=> 1,
					'W'=> 1,
					'X'=> 1,
					'Y'=> 1,
					'Z'=> 1,
				];

				$isRow = 0;
				$currentCategory = '';
				$currentLetter = '';

				while ($row = $query->fetch()) {

					$none[$row['summary']] = $row;

					if (!isset($categories[$row['category']])) {
						$currentCategory = $row['category'];

						$categories[$row['category']]['start'] = max($column)+1;
						$categories[$row['category']]['end'] = max($column)+1;

						$column['B'] = $categories[$row['category']]['start'];
						$column['C'] = $categories[$row['category']]['start'];
						$column['D'] = $categories[$row['category']]['start'];
						$column['E'] = $categories[$row['category']]['start'];
						$column['F'] = $categories[$row['category']]['start'];
						$column['G'] = $categories[$row['category']]['start'];
						$column['H'] = $categories[$row['category']]['start'];
						$column['I'] = $categories[$row['category']]['start'];
						$column['J'] = $categories[$row['category']]['start'];
						$column['K'] = $categories[$row['category']]['start'];
						$column['L'] = $categories[$row['category']]['start'];
						$column['M'] = $categories[$row['category']]['start'];
						$column['N'] = $categories[$row['category']]['start'];
						$column['O'] = $categories[$row['category']]['start'];
						$column['P'] = $categories[$row['category']]['start'];
						$column['Q'] = $categories[$row['category']]['start'];
						$column['R'] = $categories[$row['category']]['start'];
						$column['S'] = $categories[$row['category']]['start'];
						$column['T'] = $categories[$row['category']]['start'];
						$column['U'] = $categories[$row['category']]['start'];
						$column['V'] = $categories[$row['category']]['start'];
						$column['W'] = $categories[$row['category']]['start'];
						$column['X'] = $categories[$row['category']]['start'];
						$column['Y'] = $categories[$row['category']]['start'];
						$column['Z'] = $categories[$row['category']]['start'];
					}

					foreach ($index as $letter => $quarter) {
						$target = '';

						if ($row['target_open_beta_o'].' '.$row['target_open_beta_year_o'] === $quarter ) {
							$target .= ' (Open Beta)';
						}

						if ($row['target_closed_beta_o'].' '.$row['target_closed_beta_year_o'] === $quarter ) {
							$target .= ' (Closed Beta)';
						}

						if ($row['target_release_o'].' '.$row['target_release_year_o'] === $quarter ) {
							$target .= ' (GA)';
						}

						if ($target != '') {

							unset($none[$row['summary']]);

							$sheet->cells($letter.$column[$letter], function($cell) use ($row, $target) {

									$cell->setValue($row['summary'].$target);
									$cell->setAlignment('left');
									$cell->setValignment('middle');

									if ($row['released'] != 0) {
										$cell->setBackground('#7CE1FF');
									}

									$cell->setFont([
										'family' => 'Arial',
										'size' => '12'
									]);
							});

							$sheet->setSize($letter.$column[$letter], 100, 20);

							$sheet->getCell($letter.$column[$letter])
								->getHyperlink()
								->setUrl('http://issues.mediamath.com/browse/'.$row['browse']);

							$column[$letter]++;

						}

						if ($column[$letter] > $categories[$row['category']]['end']) {
							$categories[$row['category']]['end'] = $column[$letter]-1;
						}
					}
				}

				if ($config['full'] == false) {
					$categories_none = [];
					$position_none = 0;


					foreach ($none as $row) {


						if (!isset($categories_none[$row['category']])) {
							$categories_none[$row['category']] = $categories[$row['category']]['start'];
						}

						$letter_column = $letter_none.$categories_none[$row['category']];

						$sheet->cells($letter_column, function($cell) use ($row) {

								$cell->setValue($row['summary']);
								$cell->setAlignment('left');
								$cell->setValignment('middle');
								$cell->setFont([
									'family' => 'Arial',
									'size' => '12'
								]);
						});

						$sheet->setSize($letter_column, 100, 20);

						$sheet->getCell($letter_column)
							->getHyperlink()
							->setUrl('http://issues.mediamath.com/browse/'.$row['browse']);

						$categories_none[$row['category']]++;

						if ($categories_none[$row['category']] > $categories[$row['category']]['end']) {
							$categories[$row['category']]['end'] = $categories_none[$row['category']]-1;
						}
					}
				}

				foreach ($categories as $category => $rows) {
					$sheet->cells('A'.$rows['start'], function($cell) use ($category) {
							$cell->setValue($category);
							$cell->setAlignment('right');
							$cell->setValignment('middle');
							$cell->setBackground('#8CC38F');
							$cell->setFont([
								'family' => 'Arial',
								'size' => '12'
							]);
					});
					$sheet->setSize('A'.$rows['start'], 100, 20);

					$sheet->cells('A'.$rows['end'].':Z'.$rows['end'], function($cell) {
						$cell->setBorder('none', 'solid', 'solid', 'none');
					});

				}

				$sheet->setSize('A1', 100, 20);

				$sheet->freezeFirstRow();
				$sheet->setAutoSize(range('A', 'Z'));
			});

			unset($query);
			$pdo = null;
			unset($pdo);






			$pdo = DB::reconnect($config['conn'])->getPdo();
			$query = $pdo->prepare($config['queryCandidate']);

			$excel->sheet('Candidate', function($sheet) use (&$query) {

				$content = [];
				$index = [];
				$letters = range('A', 'Z');
				$query->execute();

				while ($row = $query->fetch()) {

					if (!isset($index[$row['category']])) {
						$index[$row['category']] = ' ';
						$letter = array_shift($letters);
						$column = 1;
					} else {
						$column++;
					}

					$sheet->cells($letter.'1', function($cell) use ($row) {
						$cell->setValue($row['category']);
						$cell->setAlignment('center');
						$cell->setValignment('middle');
						$cell->setBackground('#4C9900');
						$cell->setFont([
							'family' => 'Arial',
							'size' => '12',
							'bold' =>  true
						]);
					});
					$sheet->setSize($letter.'1', 100, 20);

					$sheet->getCell($letter.($column+1))
						->getHyperlink()
						->setUrl('http://issues.mediamath.com/browse/'.$row['browse']);

					$sheet->cells($letter.($column+1), function($cell) use ($row, $column) {

							$cell->setValue($row['summary']);
							$cell->setAlignment('left');
							$cell->setValignment('middle');

							if ($row['released'] != 0) {
								$cell->setBackground('#7CE1FF');
							}

							$cell->setFont([
								'family' => 'Arial',
								'size' => '12'
							]);
					});

					$sheet->setSize($letter.($column+1), 100, 20);

				}

				$sheet->setSize('A1', 100, 20);

				$sheet->setAutoFilter();
				$sheet->setAutoSize(range('A', 'Z'));
			});

			unset($query);
			$pdo = null;
			unset($pdo);

			$excel->setActiveSheetIndex(0);


		})->store('xls', storage_path('files'));
	}
}
?>
