<?php
namespace SeanMorris\Badger;
class TimedBadgeView extends \SeanMorris\Theme\View
{
}

__halt_compiler(); ?>

<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height = "30" width = "150" font-size="11">

	<style type = "text/css">
		.label   { font-family: arial;   opacity: 0.85; font-weight: bold; }
		.message { font-family: arial;   opacity: 0.95; font-weight: bold; }
		.time    { font-family: verdana; opacity: 0.75; font-weight: bold; }
		.white-text { fill: rgba(255,255,255,0.85); }
	</style>

	<defs>

		<clipPath id="round-left-corners">
			<rect x="0" y="0" width="200" height="20" rx="2" ry="2"/>
		</clipPath>

		<clipPath id="round-right-corners">
			<rect x="-65" y="0" width="150" height="20" rx="2" ry="2"/>
		</clipPath>

		<clipPath id="round-all-corners">
			<rect x="0" y="0" width="150" height="100%" rx="2" ry="2"/>
		</clipPath>

		<filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
			<feComponentTransfer><feFuncA type="linear" slope="1.25"/></feComponentTransfer>
			<feGaussianBlur stdDeviation="2 2" result="shadow"/>
			<feOffset dx="2" dy="2"/>
		</filter>

	</defs>

	<rect fill="#292929" height="100%" width="100%" y="0" x="0" clip-path="url(#round-all-corners)"/>

	<svg height="66.6%" width="65">

		<rect fill="#595959" height="100%" width="100%" y="0" x="0" clip-path="url(#round-left-corners)"/>

		<text style="filter: url(#shadow);" dominant-baseline="middle" text-anchor="middle" y="50%" x="50%">
			<tspan class = "label" fill="#000"><?=$label;?></tspan>
		</text>

		<text
			dominant-baseline="middle"
			text-anchor="middle"
			y="50%" x="50%">
			<tspan class = "label" fill="#FFF"><?=$label;?></tspan>
		</text>

	</svg>

	<svg height="66.6%" width="85" y="0" x ="65">

		<rect height="100%" width="100%" y="0" x="0" fill = "#<?=$color ?? 'a93a29';?>" clip-path="url(#round-right-corners)"/>

		<text style="filter: url(#shadow);" dominant-baseline="middle" text-anchor="middle" y="50%" x="50%">
			<tspan class = "message" fill="#000"><?=$message;?></tspan>
		</text>

		<text
			dominant-baseline="middle"
			text-anchor="middle"
			y="50%" x="50%">
			<tspan class = "message white-text"><?=$message;?></tspan>
		</text>

	</svg>

	<svg height="50%" width="100%" y="50%" x ="0">

		<text
			style="filter: url(#shadow);"
			dominant-baseline="bottom"
			text-anchor="left"
			font-size = "0.7em"
			y="50%" x="0%">
			<tspan class = "time" fill="#000"><?=$time;?></tspan>
		</text>

		<text
			dominant-baseline="bottom"
			text-anchor="end"
			font-size = "0.7em"
			y="85%" x="98%">
			<tspan class = "time" fill="#FFF"><?=$time;?></tspan>
		</text>

	</svg>
</svg>
