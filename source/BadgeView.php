<?php
namespace SeanMorris\Badger;
class BadgeView extends \SeanMorris\Theme\View
{
}

__halt_compiler(); ?>

<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height = "20" width = "150" font-size="11">
	<svg height="100%" width="65">
		<defs>

			<clipPath id="round-left-corner">
				<rect x="0" y="0" width="200" height="20" rx="2" ry="2"/>
			</clipPath>

			<clipPath id="round-right-corner">
				<rect x="-65" y="0" width="150" height="20" rx="2" ry="2"/>
			</clipPath>

			<filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
				<feComponentTransfer><feFuncA type="linear" slope="1.25"/></feComponentTransfer>
				<feGaussianBlur stdDeviation="2 2" result="shadow"/>
				<feOffset dx="2" dy="2"/>
			</filter>

		</defs>

		<rect fill="#595959" height="100%" width="100%" y="0" x="0" clip-path="url(#round-left-corner)"/>

		<text style="filter: url(#shadow);" dominant-baseline="middle" text-anchor="middle" y="50%" x="50%">
			<tspan fill="#000">
				<?=$label;?>
			</tspan>
		</text>

		<text
			dominant-baseline="middle"
			text-anchor="middle"
			y="50%" x="50%">
			<tspan fill="#FFF">
				<?=$label;?>
			</tspan>
		</text>

	</svg>

	<svg height="100%" width="85" y="0" x ="65">

		<rect height="100%" width="100%" y="0" x="0" fill = "#<?=$color ?? 'a93a29';?>" clip-path="url(#round-right-corner)"/>

		<text style="filter: url(#shadow);" dominant-baseline="middle" text-anchor="middle" y="50%" x="50%">
			<tspan fill="#000"><?=$message;?></tspan>
		</text>

		<text
			dominant-baseline="middle"
			text-anchor="middle"
			y="50%" x="50%">
			<tspan fill="#FFF"><?=$message;?></tspan>
		</text>

	</svg>
</svg>
