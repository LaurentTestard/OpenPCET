<?php

use RedBean_Facade as R;

class RConf {
	public static function conf(){
		R::setup('mysql:host=127.0.0.1;dbname=pcet','XXXX','XXXXXXXX');
	}
}
?>
