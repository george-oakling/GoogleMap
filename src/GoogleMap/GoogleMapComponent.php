<?php

namespace GoogleMap;

use Nette\Application\UI\Control;
use GeoJSON\FeatureCollection;
use GeoJSON\Point;

class GoogleMapComponent extends Control
{	
	/** implements IFeatureCollectionProvider */
	private $featureCollectionProvider;
	
	public $key, $initialCenterLatitude, $initialCenterLongitude, $mapElementId, $initialZoom, $filtersComponent, $clickEvent;

	public function setFeatureCollectionProvider($fcProvider)
	{
		$this->featureCollectionProvider = $fcProvider;
	}

	public function handleCollection($latsw = NULL, $lngsw = NULL, $latne = NULL, $lngne = NULL, $filters = array())
	{
		$presenter = $this->getPresenter();
		$fcProvider = $this->featureCollectionProvider;
		$collection = $fcProvider->getFeatureCollectionInBounds($latsw, $lngsw, $latne, $lngne, $filters);
		
		if(!is_a($collection, '\\GeoJSON\\FeatureCollection'))
			throw new \Exception('Bad type of data in $collection variable!');
		
		if($presenter->isAjax()) {
			$presenter->payload->collection = $collection;
			$presenter->sendPayload();
		} else {
			echo '<xmp>'.$collection.'</xmp>';
			$presenter->terminate();
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
		$template->collectionRetrievalAddress = $this->link('collection!');
		$template->clickEvent = $this->clickEvent ? $this->clickEvent : FALSE;
		$template->componentName = $this->name;
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