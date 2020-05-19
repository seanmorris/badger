<?php
namespace SeanMorris\Badger;
class Home implements \SeanMorris\Ids\Routable
{
	public function _dynamic($router)
	{
		$args = $router->request()->path(-1)->nodes();

		$owner = 'seanmorris';
		$project  = array_shift($args);
		$workflow = array_shift($args);

		if(!$owner || !$project || !$workflow)
		{
			return FALSE;
		}

		$context = stream_context_create(['http' => [
			'ignore_errors' => true
			, 'content'     => NULL
			, 'method'      => 'GET' //$method
			, 'header'      => [
				'Circle-Token: 0bac6b2c99a1bd1d959c0f90bbd9ae256ebf7d52'
				, 'Content-Type: application/json; charset=utf-8'
				, 'Accept: application/vnd.ksql.v1+json'
			]
		]]);

		$url = sprintf(
			'https://circleci.com/api/v2/project/gh/%s/%s/pipeline/'
			, $owner
			, $project
			, $workflow
		);

		$handle    = fopen($url, 'r', FALSE, $context);
		$rawData   = stream_get_contents($handle);
		$pipelines = json_decode($rawData);
		$lastPipe  = array_shift($pipelines->items);

		$url = sprintf(
			'https://circleci.com/api/v2/pipeline/%s/workflow'
			, $lastPipe->id
		);

		$handle    = fopen($url, 'r', FALSE, $context);
		$rawData   = stream_get_contents($handle);
		$workflows = json_decode($rawData);

		$badges = [];

		header('Content-type: application/json');

		foreach($workflows->items as $item)
		{
			if($item->name === $workflow)
			{
				header('Cache-Control: max-age=300');

				$colors = [
					'success'   => '107529'
					, 'running' => 'A89B39'
					, 'failed'  => 'a93a29'
					, 'default' => '6E9DA8'
				];

				return new BadgeView([
					'message' => htmlentities($item->status)
					, 'label' => htmlentities($_GET['label'] ?? $item->name)
					, 'color' => $colors[$item->status] ?? $colors['default']
				]);
			}

			$badges[ $item->name ] = $item->status;
		}

		header('Cache-Control: no-cache');

		header('Content-type: application/json');

		return json_encode($badges);
	}
}
