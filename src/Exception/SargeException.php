<?php

namespace Sarge\Exception;

class SargeException extends \RuntimeException {

	private $context;

	public function __construct($message , $context = array() , \Exception $previous = null ){
		$this->context = $context;
		parent::__construct($message, 0 ,$previous);
	}

	public function getContext(){
		return $this->context;
	}



}