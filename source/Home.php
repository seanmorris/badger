<?php
namespace SeanMorris\Badger;
class Home implements \SeanMorris\Ids\Routable
{
	public function index($router)
	{
		return 'Welcome to badger.';
	}
}
