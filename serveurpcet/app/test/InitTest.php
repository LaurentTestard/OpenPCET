<?php
 

use RedBean_Facade as R;
class InitTest extends PHPUnit_Framework_TestCase {

	public function testInit(){
		RConf::confTest();
	}
	
}
?>