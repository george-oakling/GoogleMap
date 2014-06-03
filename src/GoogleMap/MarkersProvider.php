<?php

namespace GoogleMap;

use Nette\Database\Context;
use Nette\Object;
/*

this is an example of IMarkersProvider interface implementation

*/

class MarkersProvider extends Object implements IMarkersProvider
{
	private $database;
	
	//private $infoWindowURL;
	
	public function __construct(Context $database)
	{
		$this->database = $database;
	}

	public function getInRectangle($latsw, $lngsw, $latne, $lngne)
	{
		$database = $this->database;
		$markers = $database->table('markers')
			->where('lat > ?', (float) $latsw)
			->where('lng > ?', (float) $lngsw)
			->where('lat < ?', (float) $latne)
			->where('lng < ?', (float) $lngne);
		
		$retval = array_map(function($row) {
			return $row->toArray();
		}, iterator_to_array($markers));
		
		return $retval;
		
	}
	
	/*public function setInfoWindowURL($url)
	{
		$this->infoWindowURL = $url;
	}
	
	public function getInfoWindowURL()
	{
		return $this->infoWindowURL;
	}*/
	
	public function getAll()
	{
		$database = $this->database;
		$markers = $database->table('markers')->fetchAll();
		$retval = array_map(function($row) {
			return $row->toArray();
		}, iterator_to_array($markers));
		
		return $retval;
	}
}