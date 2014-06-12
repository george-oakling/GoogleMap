<?php

namespace GoogleMap;

use Nette\Application\UI\Control;
use GeoJSON\FeatureCollection;
use GeoJSON\Point;

class GoogleMapComponent extends Control
{
	/** implements IMarkersProvider */
	private $markersProvider;
	
	/** TODO */
	private $clickEvent;

	private $options = array();

	public $key, $initialCenterLatitude, $initialCenterLongitude, $mapElementId, $initialZoom, $filtersComponent;

	public function setMarkersProvider(IMarkersProvider $markersProvider)
	{
		$this->markersProvider = $markersProvider;
	}

	public function handleCollection($latsw = NULL, $lngsw = NULL, $latne = NULL, $lngne = NULL, $filters = array())
	{
		// presenter is used for payload and script termination
		$presenter = $this->getPresenter();
		
		// GeoJSON FeatureCollection
		$collection = new FeatureCollection();
		
		//$collection->setBoundingBox(50, 14, 55, 15);
		
		for($i = 0; $i < 5; $i++)
		{
			$point = new Point();
			
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
			$point->addProperty("icon", $this->template->basePath."/images/spinner.gif");
			
			// add point to FeatureCollection
			$collection->addFeature($point);
		}
		
		if($presenter->isAjax()) {
			$presenter->payload->collection = $collection;
			$presenter->sendPayload();
		} else {
			echo '<xmp>'.$collection.'</xmp>';
			$presenter->terminate();
		}
	}

	public function handleMarkers($latsw = NULL, $lngsw = NULL, $latne = NULL, $lngne = NULL, $filters = array())
	{
		//dump($filters);
		$markers = array();
		$markersProvider = $this->markersProvider;
		
		if($latsw !== NULL) {
			$markers = $markersProvider->getInRectangle($latsw, $lngsw, $latne, $lngne, $filters);
		} else {
			$markers = $markersProvider->getAll($filters);
		}
		
		if($this->presenter->isAjax()) {
			$this->presenter->payload->markers = $markers;
			$this->presenter->sendPayload();
		} else {
			dump($markers);
			$this->presenter->terminate();
		}
	}

	/* render both component parts */
	public function render()
	{
		$this->renderHTML();
		$this->renderJS();
	}

	/* render HTML and JS setup part of the component */
	public function renderHTML()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/GoogleMapComponentHTML.latte');
	
		$template->markersRetrievalAddress = $this->markersProvider ? $this->link('markers!') : FALSE;
		$template->collectionRetrievalAddress = $this->link('collection!');
		$template->clickEvent = $this->clickEvent ? $this->clickEvent : FALSE;
		$template->componentName = $this->name;
		
		// if key is set, send it to template, otherwise use google map without key
		if(isset($this->key))
			$template->key = $this->key;
		
		$template->initialCenterLatitude = $this->initialCenterLatitude;
		$template->initialCenterLongitude = $this->initialCenterLongitude;
		$template->initialZoom = $this->initialZoom ? $this->initialZoom : 14;
		$template->mapElementId = $this->mapElementId ? $this->mapElementId : 'map';
		$template->filtersComponent = $this->filtersComponent;
		
		$template->render();
	}
	
	/* render generic JS, better is to use google.map.js with janmarek's webloader (after jquery load) */
	public function renderJS()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/GoogleMapComponentJS.latte');
		$template->render();
	}
}