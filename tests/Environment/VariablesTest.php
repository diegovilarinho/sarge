<?php
namespace Sarge\Test\Environment;

use PHPUnit\Framework\TestCase;
use Sarge\Environment\Variables;

class VariablesTest extends TestCase
{

    private $path = './';
    private $filename = '.env';

    protected function setUp(){
        $_ENV['STARTED'] = false;
    }

    public function testLoad()
    {	
        $array = array('var1' => '1' , 'var2' => 2 , 'var3' => 'variavel', 'MEMCACHED_SERVERS' => [ 'set' => [ ["host1" , 11211],["host2",11211] ], 'get' => [ ["host1" , 11211],["host2",11211] ] ]);
        $this->save($this->path . $this->filename , $array );
    	$this->assertCount(count($array)+1,Variables::load($this->path)); // +1 STARTED
    }

    public function testSave()
    {   
        $this->assertEquals($this->save($this->path . $this->filename , array('var1' => '1' , 'var2' => 2 , 'var3' => 'variavel')),true);
    }

    private function save($savePath , $content = array()){
        return Variables::save($savePath , $content);
    }

    protected function tearDown(){
        unlink($this->path . $this->filename);
    }

}
?>