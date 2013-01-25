<?php
$path = "./"; 

    // Open the folder 
    $dir_handle = @opendir($path) or die("Unable to open $path"); 

    // Loop through the files 
    while ($file = readdir($dir_handle)) { 

    if($file == "." || $file == ".." || $file == "index.php" ) 

        continue; 
        echo "<a href=\"$file\">$file</a><br />"; 

    } 
    // Close 
    closedir($dir_handle); 
?>