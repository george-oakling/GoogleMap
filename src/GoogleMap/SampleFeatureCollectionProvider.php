<?php

namespace GoogleMap;

use Nette\Object;
use GeoJSON;

class SampleFeatureCollectionProvider extends Object
{
	public function getFeatureCollectionInBounds($latsw, $lngsw, $latne, $lngne, $filters)
	{
		// GeoJSON FeatureCollection
		$collection = new GeoJSON\FeatureCollection();
		
		//$collection->setBoundingBox(50, 14, 55, 15);
		
		for($i = 0; $i < 5; $i++)
		{
			$point = new GeoJSON\Point();
			
			$n =  mt_rand($latsw, $latne);
			$m = mt_rand($lngsw, $lngne) ;
			//$m = 50.5;
			//$n = 15.02;
			
			// firstly set longitude, then latitude...beware!!!
			
			$point->setCoordinates($m, $n);
			$point->addProperty("id", $i."moc");
			$point->addProperty("title", $i."nic moc");
			$point->addProperty("content", "blablabla");
			$point->addProperty("href", "blablabla");
			//$point->addProperty("icon", $this->template->basePath."/images/spinner.gif");
			
			// add point to FeatureCollection
			$collection->addFeature($point);
		}
		
		return $collection;
	}
}