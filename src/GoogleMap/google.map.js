/* array of all the map markers to manipulate with them - delete old etc. */
var markers = [];

/* infowindow used for all markers, only position and content changes */
var infowindow;

/* globally accessible google map object */
var map;

/* document initialization */
$(function(){

	// start da map
	map = new google.maps.Map(
		document.getElementById(gMap.mapElementId), {
			center : new google.maps.LatLng(
				gMap.initialCenterLatitude,
				gMap.initialCenterLongitude),
			zoom : gMap.initialZoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	);
	
	// initialize infowindow object for later use
	infowindow = new google.maps.InfoWindow();

	// load new markers after each map refresh, when its idle
	
	if(gMap.markersRetrievalAddress) {
	google.maps.event.addListener(map, 'idle', function() {
	
		// count the old markers, but remove them after adding the new markers to avoid flashing
		var oldMarkersCount = markers.length;
		
		// add these coordinates (current map boundaries) to each map refresh request
		var currentMapBoundaries = {};
		currentMapBoundaries[gMap.componentName + "-latsw"] = map.getBounds().getSouthWest().lat(),
		currentMapBoundaries[gMap.componentName + "-lngsw"] = map.getBounds().getSouthWest().lng(),
		currentMapBoundaries[gMap.componentName + "-latne"] = map.getBounds().getNorthEast().lat(),
		currentMapBoundaries[gMap.componentName + "-lngne"] = map.getBounds().getSouthWest().lat()
		
		// get the markers position and add them to the map
		$.getJSON(
			gMap.markersRetrievalAddress,
			currentMapBoundaries,
			function(data) {
				
				if(typeof data.markers !== "undefined") {
					
					$.each(data.markers, function(i, item) {
			
						// add marker to map
						var position = new google.maps.LatLng(item.lat, item.lng);
						var marker = new google.maps.Marker({
							position: position,
							map: map,
							title: item.title
						});
				
						// add to global markers array
						markers.push(marker);
				
						// append infowindow to each marker
						if(item.content) {
							google.maps.event.addListener(marker, 'click', function() {
								infowindow.close();
								infowindow.setContent(item.content);
								infowindow.open(map, marker);
							});
						}
					});
				}
			}
		);
		
		// remove old markers, which are out of bounds or are replaced with the new set of markers
		markers.splice(0, oldMarkersCount);
	});
	}
	
	// clickable events
	if(gMap.clickEvent) {
		google.maps.event.addListener(map, "click", function(event) {
		
			if(gMap.clickEventShowMarker) {
				var marker = new google.maps.Marker({
					position: event.latLng,
					map: map
				});
			}
		
			var dialog = new google.maps.InfoWindow();
		
			$.get(gMap.clickEvent, function(data) {
				dialog.setContent(data);
				//alert("Load was performed.");
			});
		
			// tooooodooooo lots of tooodoooo
			
			google.maps.event.addListener(marker, "click", function() {
				dialog.open(map, marker);
			});
		
		});
	}
	
});
