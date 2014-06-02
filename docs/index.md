GoogleMap - component for Nette
-------------------------------


Requirements
------------

- Nette >= v2.x
- JQuery >= v1.8


Installation
------------

Ideally by composer:
composer require george-oakling/googlemap:dev-master

Or download a zip and put it in the vendors/other:
...


Usage
-----

Use this component as usual components, with setting up in the component code, e.g.:

use GoogleMap;

public function createComponentGoogleMap()
{
  $gmap = new GoogleMap\GoogleMapComponent();
  return $gmap;
}

{control googleMap}

If you want to add some configuration of Google map component, do it like this:

$markersProvider = new SampleMarkersProvider();

$gmap = new GoogleMap\GoogleMapComponent();
$gmap->setMarkersProvider($markersProvider);

$gmap->setOptions()
{
}

TODO...

