<?php

namespace GoogleMap;

interface IFeatureCollectionProvider
{
	/** returns GeoJSON\FeatureCollection */
	function getFeatureCollectionInBounds($latsw, $lngsw, $latne, $lngne, $filters);
}
