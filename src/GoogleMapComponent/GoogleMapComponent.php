<?php

namespace GoogleMapComponent;

use Nette\Application\UI\Control;

class GoogleMapComponent extends Control
{

	public function handleMarkers()
	{
		//$this->payload->markers = file_get_contents(__DIR__.'/markers.json');
		//$this->payload
		$this->presenter->sendPayload();
	}

	/* render all */
	public function render()
	{
		$this->renderHTML();
		$this->renderJS();
	}

	/* render just HTML part of the component */
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
	
	/* if you use janmarek's webloader, than this is useless for you and instead of this, add google.map.js to files loaded with webloader */
	public function renderJS()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/GoogleMapComponentJS.latte');
		$template->render();
	}
}