GoogleMap - component for Nette
==========================


Requirements
------------

- Nette >= v2.2.x
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

Markers for map
---------------

After a long thinking, I found that Google Maps Javascript API implements support for GeoJSON format, which is perfectly describing all my needs as of representing markers on map, multipoints and other geodata. So I implemented an easy PHP object, which respects the internal structure of GeoJSON implementation and added this as the default method of implementing markers of map.

Markers in GeoJSON are represented as an array of Features objects, where each Feature object has a type of Point. Other geodata, which are not yet implemented are MultiPoint, Line, MultiLine, Polygon, etc. More can be found at www.geojson.org.

The array of markers is appended to the main FeatureCollection object, which is then sent to Google Maps API.

Better one example than ten thousands words (this example includes the use of SampleFeatureCollectionProvider, which is included in the component sources as an example):


```php

use GoogleMap;

class MapPresenter
{
	public function createComponentGoogleMap()
	{
  		$gmap = new GoogleMap\GoogleMapComponent();
  		$fcProvider = new GoogleMap\SampleFeatureCollectionProvider();
  		$gmap->setFeatureCollectionProvider($fcProvider);
		return $gmap;
	}
}
```

Easy hmmm? Now, after each map change of boundaries, zoom or whatever, the AJAX part of GoogleMapComponent calls the feature collection provider to update the list of markers in current boundaries. Do you want to get the markers from database? Easy like this:

```php

use Nette;
use GeoJSON;

class DbFCProvider
{
	private $database;
	
	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}
	
	public function getFeatureCollectionInBounds($latsw, $lngsw, $latne, $lngne, $filters = array())
	{
		// get markers in bounds
		$markers = $this->database->table('markers')
			->where('lat >', $latsw)
			->where('lat <', $latne)
			->where('lng >', $lngsw)
			->where('lng <', $lngne);
		
		// TODO: apply filters
		foreach($filters as $filter)
		{
			// do something
		}
		
		// GeoJSON feature collection init
		$collection = new GeoJSON\FeatureCollection();
		
		// cycle through markers
		foreach($markers as $marker)
		{
			// markers are represented as GeoJSON Point object, which is an extension to GeoJSON Feature object
			$point = new GeoJSON\Point();
			
			// WARNING: first param is Longitude, second is Latitude!
			$point->setCoordinates($marker->lng, $marker->lat);
			
			// you can also set other properties, which from are some automatically doing something :)
			
			// marker's title on hovering on marker
			$point->addProperty("title", $marker->title);
			
			// marker's icon source - image path
			$point->addProperty("icon", $marker->icon);
			
			// marker's content which is represented by infowindow, when you click on marker
			$point->addProperty("content", $marker->content);
			
			// marker's anchor - if you want to have an anchor in the infowindow of each marker, just add the href property and each infowindow will have direct <a href> after the content
			$point->addProperty("href", $marker->href);
			
			// now its time to put the Point into FeatureCollection
			$collection->addFeature($point);
			
		}
		
		// everything is OK, lets return the collection;
		return $collection;
		
		
	}
}
```

TODOOOOO.....

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

