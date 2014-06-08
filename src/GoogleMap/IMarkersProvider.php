<?php

namespace GoogleMap;

interface IMarkersProvider
{
	function getInRectangle ($latsw, $lngsw, $latne, $lngne, $filters);
	
	function getAll($filters);
}