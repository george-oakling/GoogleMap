/* array of all the map markers to manipulate with them - delete old etc. */
var markers = [];

/* infowindow used for all markers, only position and content changes */
var infowindow;

/* globally accessible google map object */
var map;

/* document initialization */
$(function(){
	
	var mapOptions = $('div#map').data();
	var dialog = new google.maps.InfoWindow();
	//console.log(mapOptions);
	
	// start da map
	map = new google.maps.Map(
		document.getElementById('map'), {
			center : new google.maps.LatLng(
				mapOptions.initialCenterLat,
				mapOptions.initialCenterLng),
			zoom : mapOptions.initialZoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: true,
			draggable: true
		}
	);

	// do we have collections of markers in GeoJSON format?
	if(mapOptions.collectionRetrievalAddress) {
		// load new markers after each map refresh, when its loaded and idle
		google.maps.event.addListener(map, 'idle', function() {
			// add these coordinates (current map boundaries) to each map refresh request
			var markersRetrievalOptions = {};
			markersRetrievalOptions[mapOptions.componentName + "-latsw"] = map.getBounds().getSouthWest().lat();
			markersRetrievalOptions[mapOptions.componentName + "-lngsw"] = map.getBounds().getSouthWest().lng();
			markersRetrievalOptions[mapOptions.componentName + "-latne"] = map.getBounds().getNorthEast().lat();
			markersRetrievalOptions[mapOptions.componentName + "-lngne"] = map.getBounds().getSouthWest().lat();
			markersRetrievalOptions[mapOptions.componentName + "-filters"] = $("#frm-"+mapOptions.filtersComponent).serializeArray();
		
			// GeoJSON collection update - very easy!
			$.get(
				mapOptions.collectionRetrievalAddress,
				markersRetrievalOptions,
				function(payload) {
					map.data.addGeoJson(payload.collection, { idPropertyName: 'id' });
				}
			);
		
			map.data.setStyle(function(feature) {
				return({
					icon: feature.getProperty('icon'),
					title: feature.getProperty('title')
				});
			});
		
		});
	}
	
	// are there markers in data layer?
	if(map.data) {
		
		// open infowindow for every marker with marker info details
		map.data.addListener('click', function(event) {
			
			var feature = event.feature;
			var anchor = new google.maps.MVCObject();
			var container = $('<div/>');
			var content = $('<div/>', {
				id: 'map-infowindow',
				class: 'map-infowindow'
			});
			
			dialog.close();
			anchor.set('position', event.latLng);
			
			// if title, add h2 title
			if(feature.getProperty('title')) {
				$(content).append(
					$('<h2/>', {
						text: feature.getProperty('title')
					})
				);
			}
			
			//if content, add div of content
			if(feature.getProperty('content')) {
				$(content).append(
					$('<div/>', {
						html: feature.getProperty('content')
					})
				);
			}
			
			// if href add an anchor
			if(feature.getProperty('href')) {
				
				var href = $('<a/>', {
					href: feature.getProperty('href'), 
					title: feature.getProperty('title'), 
					text: 'detail',
					class: 'ajax',
					id: 'dialogAnchor'
				});
				
				/* nette ajax for Google maps */
				if($.nette !== undefined) {
					google.maps.event.addDomListener(dialog, 'domready', function() {
						$('#map-infowindow').on('click', '.ajax', function(e) {
							e.preventDefault();
							$.nette.ajax(this.href, this, e);
						});
					});
				}
				 				
				$(content).append($(href));
			}
			
			$(container).append($(content));
			
			dialog.setOptions({
				pixelOffset: 
					{ 
						height: -40, 
						width: 0
					}
			});
			
			
			dialog.setContent($(container).html());
			dialog.open(map, anchor);
			
		});
	}
});
