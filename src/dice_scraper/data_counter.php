<?php
	
	$fp = file('dice_data_scrape_'.$_GET['file'].'.csv', FILE_SKIP_EMPTY_LINES);
	echo count($fp)-1;
	
?>