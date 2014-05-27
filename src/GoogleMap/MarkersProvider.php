<?php

namespace GoogleMap;

use Nette\Database\Context;

class MarkersProvider implements IMarkersProvider
{
	private $database;
	
	public function __construct(Context $database)
	{
		$this->database = $database;
	}

	public function getInRectangle()
	{
	}
	
	public function getAll()
	{
	}
}