<?php

namespace GoogleMap;

use Nette\Application\UI\Control;

class GoogleMapComponent extends Control
{
	private $markersProvider;

	public function __construct(IMarkersProvider $markersProvider) {
		$this->markersProvider = $markersProvider;
	}

	public function handleMarkers()
	{
		
		$this->presenter->sendPayload();
	}

	/* render all */
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
	
		// map settings
		$template->key = 'AIzaSyCwqfZYn6EDGvMHw3GfyP4vcW944Lq1Pi0';
		$template->initialCenterLatitude = 50.083;
		$template->initialCenterLongitude = 14.423;
		$template->link = $this->link('markers!');
		$template->mapElement = 'map';
		$template->mapType = 'ROADMAP';
		$template->zoom = $this->template->initialZoom = 12;
		
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