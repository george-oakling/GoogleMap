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

You must include JQuery before the {control googleMap}, or if you use scripts block, or janmarek/webloader, you should use alternative component rendering, which is mentioned below.

There is no need for additional parameters, but usually you want to add some actions and handlers to map.

1. For handling the markers, which will be put on the map, you have IMarkersProvider interface, which is very simple and includes two methods. Sample MarkersProvider is in sources. The use is easy:

```php
$markersProvider = new GoogleMap\MarkersProvider();
$gmap->setMarkersProvider($markersProvider);
```

2. You want to set intial center of map, zoom or map element id and your own Google Maps API key? No problem at all:

```php
$gmap->initialCenterLatitude = 50.083;
$gmap->initialCenterLongitude = 14.423;
$gmap->initialZoom = 12;
$gmap->mapElementId = 'map';
$gmap->key = 'AIzAxxx:-)';
```

There are actually two parts of the GoogleMap component. First one is needed HTML, JS scripts for map options, this can be rendered with this command:

```
{control googleMap:HTML}
```

The second part is Javascript code, which operates with the map object. This part works quite good with janmarek/webloader component, all you need is add google.map.js in component folder to webloader's JS definition, or you can add below lines to block like this:

```
{block scripts}
	{include parent}
	{control googleMap:JS}
{/block scripts}
```

