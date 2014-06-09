<?php

// TODO!!!

namespace GoogleMap\DI;

use Nette;

/**

extensions:
	googlemap: GoogleMap\DI\GoogleMapExtension
	
googlemap:
	apiKey: xxx
	other stuff

*/
class GoogleMapExtension extends Nette\DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$config = $this->getConfig();
		// array
	}
}
