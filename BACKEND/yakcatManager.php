<?php 

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$yakcat = $db->category;
/*
$placeColl->save(array("title"=>"Actualit�s",
                        "content"=>"Les news",
                        "tags"=>"news",
                        "creationDate" => mktime(),
                        "lastModifDate" => mktime(),
                        "status" =>0,
                        "user" => 0
                     )              
                    );
                    */
                    

?>