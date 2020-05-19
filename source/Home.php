<?php
namespace SeanMorris\Badger;

use \SeanMorris\Ids\Routable, \SeanMorris\Ids\Settings;

class Home implements Routable
{
	public function index($router)
	{
		header('HTTP/1.1 200 MUSHROOM MUSHROOM');

		return new HomeView;
	}

	public function _dynamic($router)
	{
		header('Cache-Control: no-cache');

		$args = $router->request()->path(-1)->nodes();

		$circleConfig = Settings::read('circleci');

		$owner    = $circleConfig->username;
		$project  = array_shift($args);
		$workflow = array_shift($args);
		$branch   = array_shift($args) ?: 'master';

		if(!$project)
		{
			return FALSE;
		}

		$context = stream_context_create(['http' => [
			'ignore_errors' => true
			, 'content'     => NULL
			, 'method'      => 'GET' //$method
			, 'header'      => [
				'Circle-Token: ' . $circleConfig->token
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

		foreach($pipelines->items as $item)
		{
			if(!$branch || $branch === $item->vcs->branch)
			{
				$lastPipe = $item;
				break;
			}
		}

		if($lastPipe??0)
		{
			$url = sprintf(
				'https://circleci.com/api/v2/pipeline/%s/workflow'
				, $lastPipe->id
			);

			$handle    = fopen($url, 'r', FALSE, $context);
			$rawData   = stream_get_contents($handle);
			$workflows = json_decode($rawData);
		}

		$colors = [
			'success'   => '107529'
			, 'running' => 'A89B39'
			, 'failing' => 'a93a29'
			, 'default' => '6E9DA8'
		];

		if($workflows??0)
		{
			$badges = [];

			foreach($workflows->items as $item)
			{
				if($item->name === $workflow)
				{
					header('Content-type: image/svg+xml');

					$status = $item->status;

					if($status === 'success')
					{
						$status = 'passing!';
					}

					if($status === 'failed')
					{
						$status = 'failing!';
					}

					return new BadgeView([
						'message' => htmlentities($status)
						, 'label' => htmlentities($_GET['label'] ?? $item->name)
						, 'color' => $colors[$item->status] ?? $colors['default']
					]);
				}

				$badges[ $item->name ] = $item->status;
			}

			header('Content-type: application/json');

			return json_encode($badges);
		}

		return new BadgeView([
			'message' => htmlentities('Error')
			, 'label' => htmlentities('!')
			, 'color' => $colors['default']
		]);
	}
}
