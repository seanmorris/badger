<?php
namespace SeanMorris\Eventi;
class Home implements \SeanMorris\Ids\Routable
{
	public function index($router)
	{
		return 'Welcome to badger.';
	}
}
