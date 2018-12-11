<?php

namespace Sarge\Environment;

use Sarge\Environment\Exception\VariablesException as VariablesException;
use Dotenv\Dotenv as EnvironmentHandler;
use Sarge\SargeSoldier;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

//@TODO refatorar todo o tratamento de array
class Variables extends SargeSoldier implements VariablesInterface {

	public static function load( $loadPath , $envFile = null ){
		try{
			if (FALSE == getenv('STARTED')){

				if (!is_dir( $loadPath ))
					throw new \RuntimeException('Cannot find directory '.$loadPath);

			    $environment = new EnvironmentHandler( $loadPath , $envFile );
			    $environment->load();

			    $_SERVER['MEMCACHED_SERVERS.get'] = Variables::hostsToEnv('MEMCACHED_SERVERS.get');
			    $_SERVER['MEMCACHED_SERVERS.set'] = Variables::hostsToEnv('MEMCACHED_SERVERS.set');

			    $_SERVER['STARTED'] = true;

			    $_ENV['MEMCACHED_SERVERS.get'] = $_SERVER['MEMCACHED_SERVERS.get'];
			    $_ENV['MEMCACHED_SERVERS.set'] = $_SERVER['MEMCACHED_SERVERS.set'];

			    $_ENV['STARTED'] = true;

                $get_servers = [];
                if ($_ENV['MEMCACHED_SERVERS.get']){
	                $get_servers = [];
	                foreach($_ENV['MEMCACHED_SERVERS.get'] as $get){
	                    $get_servers[] = implode(':',$get);
	                }
	                putenv('MEMCACHED_SERVERS.get='.implode(',',$get_servers));
                }
                if ($_ENV['MEMCACHED_SERVERS.set']){
	                $set_servers = [];
	                foreach($_ENV['MEMCACHED_SERVERS.set'] as $set){
	                    $set_servers[] = implode(':',$set);
	                }
	                putenv('MEMCACHED_SERVERS.set='.implode(',',$set_servers));
                }
			}
			return $_ENV;
		} catch (Exception $e){
			return false;
		}
	}

	private static function hostsToEnv( $key ){
		if (FALSE === getenv($key))
			return false;
		$hostsArray = explode(',' , getenv($key));
		foreach($hostsArray as $host){
			$server = explode(':' , $host);
			if (count($server) != 2)
				throw new \RuntimeException('Server array must contain only HOST:PORT');
			$r[] = $server;
		}
		return $r;
	}

	public static function save( $savePath , $content = array() ){
		try{
			$string = '';
			foreach ($content as $key => $value){
				if ('MEMCACHED_SERVERS' == $key){
					if (isset($value['set'])){
						$string_set = '';
						foreach($value['set'] as $set){
							$string_set[] = $set[0].':'.$set[1];
						}
						$string .= "MEMCACHED_SERVERS.set='".implode(',',$string_set)."'\n";
					}
					if (isset($value['get'])){
						$string_get = '';
						foreach($value['get'] as $get){
							$string_get[] = $get[0].':'.$get[1];
						}
						$string .= "MEMCACHED_SERVERS.get='".implode(',',$string_get)."'\n";
					}
				}else if (is_string($value))
					$string .= $key ."='".$value."'\n";
			}

			if ( !file_put_contents( $savePath , $string ) )
				throw new \RuntimeException('Cannot save to output file');
			return true;
		} catch (Exception $e){
			throw new VariablesException($e->getMessage() , [ 'path' => $savePath , 'content' => $content ] );
		}
	}

}

