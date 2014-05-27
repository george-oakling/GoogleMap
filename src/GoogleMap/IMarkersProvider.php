<?php

namespace GoogleMap;

interface IMarkersProvider
{
	function getInRectangle();
	
	function getAll();
}