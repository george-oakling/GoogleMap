GoogleMap - component for Nette
==========================


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

1. For handling the markers, which will be put on the map, you have IMarkersProvider interface, which is very simple and includes two methods. Sample SampleMarkersProvider is in sources. The use is easy:

```php
$markersProvider = new GoogleMap\SampleMarkersProvider();
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

There are actually two parts of the GoogleMap component. First one is needed HTML and JS scripts for map options, this can be rendered with this command:

```
{control googleMap:HTML}
```

The second part is Javascript code, which operates with the map object. This part works quite good with janmarek/webloader component, all you need is to add google.map.js in component folder to webloader's JS definition, or you can add below lines to block like this:

```
{block scripts}
	{include parent}
	{control googleMap:JS}
{/block scripts}
```


IMarkersProvider implementation
------------------------------

The IMarkersProvider interface is quite easy to understand, but it has to implement the functions of getInRectangle and getAll, which return array of markers, which is described here:

```php
$markers = array(
	[0] => array(
		'lat' => 50,
		'lng' => 14,
		'title' => 'Prague',
		'content' => 'Welcome to Prague, and enjoy your vacation! We have <strong>metro</strong>!'
		// content can have HTML inside, this will be then opened in infowindow
	)
);

```

Then the GoogleMapComponent transfers these objects into one JSON object, which is sent to the map by AJAX request and then handled by the Javascript in the google.map.js file. The JSON file looks like this:

```json
{
"markers":
	[
		{
			"lat": 50,
			"lng": 14,
			"title": "Hi, I am your marker!",
			"content": "I'm a <strong>barbie</strong> girl, in a barbie world!"
		}
	]
}
```

Howto include into marker infowindow a link to some presenter with marker special params?
----------------------------------------------------------------

TODO - implementation still not clean enough.


Click-on-map event
-------------------

TODO - implementation is TBD.

