GoogleMap - component for Nette
==========================

Requirements
------------
- Nette >= v2.2.x
- JQuery >= v1.8
- george-oakling/geojson


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

You must include JQuery before the ```{control googleMap}```, or:
- if you use scripts block, put ```{control googleMap:HTML}``` where you want your map and ```{control googleMap:JS}``` where you have scripts block, e.g. ```{block scripts}{include parent}{control googleMap:JS}{/block}```
- if you use janmarek's webloader, just put a newline at javascript definitions like this: ```%pathToGoogleMapComponent%/google.map.js``` and than put ```{control googleMap:HTML}``` where you want your map

WARNING: map is hardcoded represented as ```<div id="map"></div>``` and you have to specify the height of map by CSS. If you dont specify the height, map will be shown, but only zero pixel height, so practically nothing will be shown and you will be thinking, why WTF? Why?

You also want to set the intial center of map, zoom and your own Google Maps API key. It can be done like this:

```php
$gmap->initialCenterLatitude = 50.083;
$gmap->initialCenterLongitude = 14.423;
$gmap->initialZoom = 12;
$gmap->key = 'AIzAxxx:-)';
```

These parametres are added as data attributes to ```<div id="map"></div>```, so the Javascript code can receive them and operate with them accordingly.

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

