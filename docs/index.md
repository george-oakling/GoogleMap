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

1. For handling the markers, which will be put on the map, you have IMarkersProvider interface, which is very simple and includes two methods. Sample MarkersProvider is in sources. The use is easy:

```php
$markersProvider = new GoogleMap\MarkersProvider();

$gmap = new GoogleMap\GoogleMapComponent();
$gmap->setMarkersProvider($markersProvider);
```

2. You want to set intial center of map, zoom or map element id? No problem at all:

```php
$gmap->initialCenterLatitude = 50.083;
$gmap->initialCenterLongitude = 14.423;
$gmap->initialZoom = 12;
$gmap->mapElementId = 'map';
```