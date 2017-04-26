<?php
class NavigationMenuReport
{
	public static function get($options)
	{
		try {
			$output = [];
			$config = Config::get('reports/report');



			foreach ($options as $opt) {

				//$config[$opt]

				if (isset($config[$opt]['sub']) && !empty($config[$opt]['sub']) ) {

					if (User::hasRole($config[$opt]['role'])) {
						$output[$opt] = $config[$opt];
						$output[$opt]['sub'] = [];

						foreach ($config[$opt]['sub'] as $iSC => $subCategories) {

							if (User::hasRole($subCategories['role'])) {
								$output[$opt]['sub'][$iSC] = $config[$opt]['sub'][$iSC];

								if (isset($subCategories['sub']) && !empty($subCategories['sub']) ) {
									$categories = $output[$opt]['sub'][$iSC];
									unset($output[$opt]['sub'][$iSC]['sub']);

									foreach ($categories['sub'] as $iC => $category) {
										if (User::hasRole($category['role'])) {
											$output[$opt]['sub'][$iSC]['sub'][$iC] = $category;
										}
									}
								}
							}
						}
					}
				} else {
					if (User::hasRole($config[$opt]['role'])) {
						$output[$opt] = $config[$opt];
					}

				}
			}
			return $output;

		} catch(Exception $e) {
			return [];
		}
	}

	public static function mainPage()
	{
		return self::get(
			[
				'media',
				'audience-management',
				'helix',
				'financials',
				'open-analytics',
				'cso'
			]
		);
	}
}
