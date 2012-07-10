<?php 

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$yakcat = $db->category;
/*
$placeColl->save(array("title"=>"Actualités",
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