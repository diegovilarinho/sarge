<?php

namespace Sarge;

class SargeSoldier {

	const VERSION = 1.0;

	private $environment;

	public function getEnvironment(){
		return $this->environment;
	}

	public function setEnvironment( $env ){
		$this->environment = $env;
	}

}