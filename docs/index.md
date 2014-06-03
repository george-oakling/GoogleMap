GoogleMap - component for Nette
-------------------------------


Requirements
------------

- Nette >= v2.x
- JQuery >= v1.8


Installation
------------

Ideally by composer:

```
composer require george-oakling/googlemap:dev-master
```

Or download a zip and put it in the vendors/others.


Usage
-----

```php
use GoogleMap;

public function createComponentGoogleMap()
{
  $gmap = new GoogleMap\GoogleMapComponent();
  return $gmap;
}

{control googleMap}
```

There is no need for additional parameters, but usually you want to add some actions and handlers to map.

1. For handling the markers, which will be put on the map, you have IMarkersProvider interface, which is very simple and includes two methods. Sample MarkersProvider is in sources. The 


If you want to add some configuration of Google map component, do it like this:

$markersProvider = new SampleMarkersProvider();

$gmap = new GoogleMap\GoogleMapComponent();
$gmap->setMarkersProvider($markersProvider);

$gmap->setOptions()
{
}

TODO...

