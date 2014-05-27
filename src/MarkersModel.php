<?php

class MarkersModel
	extends Nette\Object
	implements IMarkers
{
	private $database;

	public function __construct(Nette\Database\Context $database) {
		parent::__construct();
		$this->database = $database;
	}

	public function getInRectangle() {
		return true;
	}
	
	public function getAll() {
	}
}