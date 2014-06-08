<?php

namespace GoogleMap;

use Nette\Application\UI\Control;

class GoogleMapComponent extends Control
{
	/** implements IMarkersProvider */
	private $markersProvider;
	
	/** TODO */
	private $clickEvent;

	public $key, $initialCenterLatitude, $initialCenterLongitude, $mapElementId, $initialZoom, $filtersComponent;

	public function setMarkersProvider(IMarkersProvider $markersProvider)
	{
		$this->markersProvider = $markersProvider;
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
		
		$this->presenter->payload->markers = $markers;
		$this->presenter->sendPayload();
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