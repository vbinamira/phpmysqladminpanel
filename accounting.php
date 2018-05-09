<?php 
	$file = fopen("extracts/SalesJan2009.csv","r");
	print_r(fgetcsv($file));
	fclose($file);
 ?>