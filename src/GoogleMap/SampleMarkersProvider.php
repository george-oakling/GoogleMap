<?php

namespace GoogleMap;

use Nette\Object;

class SampleMarkersProvider extends Object implements IMarkersProvider
{
	private $markers = array (
		[0] => array (
			'lat' => 50,
			'lng' => 14.5,
			'title' => 'Prague',
			'content' => 'Whatever',
		),
	);

	public function getAll() {
		return $this->markers;
	}
	
	public function getInRectangle($latsw, $lngsw, $latne, $lngne) {
		return $this->markers;
	}
}