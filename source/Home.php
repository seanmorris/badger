<?php
namespace SeanMorris\Badger;
class Home implements \SeanMorris\Ids\Routable
{
	public function circle($router)
	{
		$args = $router->request()->path()->consumeNodes();

		$owner    = array_shift($args);
		$project  = array_shift($args);
		$workflow = array_shift($args);

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

		foreach($workflows->items as $item)
		{
			if($item->name === $workflow)
			{
				header('Content-type: image/svg+xml');

				return new BadgeView([
					'message' => htmlentities($item->status)
					, 'label' => htmlentities($_GET['label'] ?? $item->name)
					, 'color' => $item->status === 'success'
						? '107529'
						: 'a93a29'
				]);
			}

			$badges[ $item->name ] = $item->status;
		}

		header('Content-type: application/json');

		return json_encode($badges);
	}
}
