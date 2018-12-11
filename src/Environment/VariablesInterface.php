<?php

namespace Sarge\Environment;

interface VariablesInterface {
	public static function load( $loadPath , $envFile = null );
	public static function save( $savePath , $content = null );
}