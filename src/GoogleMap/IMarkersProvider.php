<?php

namespace GoogleMap;

interface IMarkersProvider
{
	function getInRectangle ($latsw, $lngsw, $latne, $lngne);
	
	function getAll();
}