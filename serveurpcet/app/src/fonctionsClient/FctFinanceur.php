<?php
use RedBean_Facade as R;

class FctFinanceur {
		
	public static function formeFinanceurArray($financeur) {
		$financeurTab = array();
		$financeurTab['id']=$financeur->id;

		return $financeurTab;
	}

}
?>
