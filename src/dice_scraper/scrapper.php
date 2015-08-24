<?php
	$conn_checker = time();
	$conn_closed=fopen('connection_timeout.txt','w+');
	fwrite($conn_closed,$conn_checker);
	fclose($conn_closed);
	ini_set('max_execution_time', 1200);
	$all_data=array();
	$query_string ="http://service.dice.com/api/rest/jobsearch/v1/simple.json?";
	$text=urlencode($_GET['text']);
	$text=str_replace('%22','"',$text);
	$text=str_replace('+',' ',$text);
	$country=$_GET['country'];
	//$skill=urlencode($_GET['skill']);
	$state=($_GET['state']);
	$age=urlencode($_GET['age']);
	if(isset($_GET['city'])){
		$city=urlencode($_GET['city']);
		$city=str_replace('%2C',',',$city);
		$query_string.="country=$country&city=$city&text=$text&state=$state&age=$age&pgcnt=50";
	}else
		$query_string.="country=$country&text=$text&state=$state&age=$age&pgcnt=50";
	
	
	$count=0;
	$still_has=true;
	$file_name=$_GET['file'];
	$file=fopen('dice_data_scrape_'.$file_name.'.csv','w+');
	
	/*if(isset($city)){
		$only_state=false;
	}else{
		$only_state=true;
	}*/
	fputcsv($file,array('Detail URL','Job Title', 'Company','Location'));

	while($still_has){
		
		$json_data=json_decode(file_get_contents($query_string));
		
		//if(!$only_state){
			$count=$json_data->count;
			foreach($json_data->resultItemList as $job){
				/*if(!is_same_connection()){	
					fclose($file);
					die();
				}*/
				//echo 'job: ' . $job->jobTitle . '     location: '.$job->location.'<br>';
				fputcsv($file,array($job->detailUrl,$job->jobTitle,$job->company,$job->location));	
			}
		/*}else{
			foreach($json_data->resultItemList as $job){
				/*if(!is_same_connection()){	
					fclose($file);
					die();
				}
				$location = $job->location;
				echo 'job: ' . $job->jobTitle . '     location: '.$location.'<br>';
				if(substr($location,strlen($location)-2)==$state){
					fputcsv($file,array($job->detailUrl,$job->jobTitle,$job->company,$job->location));	
					echo 'its in<br>';
					$count++;
				}
			}
		}*/
		if(!property_exists($json_data,'nextUrl'))
			$still_has=false;
		else
			$query_string='http://service.dice.com'.$json_data->nextUrl;
	}
	fclose($file);
	echo $count;
	
	
	function is_same_connection(){
		global $conn_checker;
		$check = fopen('connection_timeout.txt','r');
		$data = fread($check,filesize('connection_timeout.txt'));
		fclose($check);
		if($data==$conn_checker)
			return true;
			
		else
			return false;
			
	}
?>