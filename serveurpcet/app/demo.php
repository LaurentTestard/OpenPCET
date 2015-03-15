<?php 
require_once '../vendor/autoload.php';
use RedBean_Facade as R;
R::setup('mysql:host=localhost;dbname=book','root','');
R::nuke();


$strTableBook = "bookquantiquetest";
$strTableArmoire = "armoire";
$strTableGenre = "genre";


$armoire = R::dispense( $strTableArmoire );
$armoire->name = "armoire 1";
$armoire->porte = 2;
R::store( $armoire );	

$b = R::dispense( $strTableBook );
$b->title = 'book1';
$b2 = R::dispense( $strTableBook );
$b2->title = 'book2';
//$b2->sharedArmoire[] = $armoire;
R::store( $b );
R::store( $b2 );


$armoire2 = R::dispense( $strTableArmoire );
$armoire2->name = "armoire 2";
$armoire2->porte = 1;
$armoire2->sharedBookquantique[] = $b2;
R::store( $armoire2 );


$genre1 = R::dispense( $strTableGenre );
$genre1->name = "genre 1";
$genre1->sharedBookquantique[] = $b;
R::store( $genre1 );



$books = R::findAll( $strTableBook );
foreach($books as $book) echo $book->title."<br />"; echo "<hr/>";

$armoires = R::findAll( $strTableArmoire );
foreach($armoires as $armoire) echo $armoire->name."<br />"; echo "<hr/>";

$genres = R::findAll( $strTableGenre );
foreach($genres as $genre) echo $genre->name."<br />"; echo "<hr/>";


echo "Selection recursive<br />";
foreach($books as $book){
	//echo "oky";
	echo $book->title."<br />";
	foreach($book->sharedArmoire as $armoire){
		echo "a -->".$armoire->name."<br />";
	}
	foreach($book->sharedGenre as $genre){
		echo "g -->".$genre->name."<br />";
	}
}

?>