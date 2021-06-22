<?php

function String($str){
	$str = htmlentities($str);
	$str = trim($str);
	$str = stripslashes($str);
	$str = htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	return $str;
}

function Validate_Password($password){
    $password = String($password);
    if (strlen($password) > 5){
        return true;
    }
}

function validate_user($int){
	
	$int = String($int);
	if (preg_match('%^[0-9\.\'\-]$%', stripslashes(trim($int)))){
		return true;
	}
	return false;
}

function User_Input($string){
	
	$string = String($string);
	if(preg_match('%^[A-Za-z\.\'\-]{2,30}$%', stripcslashes(trim($string)))){
		return true;
	}
	return false;
}
function Phone_Input($str){
	
	$str = String($str);
	if ((preg_match("/\b(?:(?:080|070|090|081|091))[0-9]{8}+$/", $str) === 1)){
		return true;
	}
	return false;
}
function val_amount($amount){
	
	$amount = String($amount);
	if (preg_match('%^[0-9\.\'\-]{3,7}$%', stripslashes(trim($amount)))){
		return true;
	}
	return false;
}
function Email_Input($email){
	
	$email = String($email);
	if (preg_match('%^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$%', stripslashes(trim($email)))){
		return true;
	}
	return false;
}

 function translate_Date($date){
	
	$syc = explode("-",$date);
	$return_day = $syc[2].'-'.$syc[1].'-'.$syc[0] . ' ' . date('h:m:s');
	return $return_day;
}
function cal_days($current_date,$pay_date){
	
	$c = new DateTime($current_date);
	$p = new DateTime($pay_date);
	$d = $p->diff($c);
	return $d->days;
}
function cal_interest($interest,$amount){
	
	$result = $interest/100 * $amount;
	return $result;
}
function translate_Date1($date){
	
	$syc = explode("/",$date);
	$return_day = $syc[2].'-'.$syc[1].'-'.$syc[0] . ' ' . date('h:m:s');
	return $return_day;
}
//Translate New Day to Timestamp
 function timestamp($date){
	 
	$return = strtotime($date);
	return $return;
}
//Get Days Difference
function days_difference($start,$end){
	
	$start_day = new DateTime($start);
	$end_day = new DateTime($end);
	$diff = $end_day->diff($start_day);
	return $diff->days;
}
function target_interest_rate($days){
	
	$cal = $days * 0.03;
	return $cal;
}
 function process_target_daily_amount($start,$end,$amount){
	 
	//Get the days difference
	$days_diff = cal_days($start, $end);
	//Divide the target amount by the set amount
	$calculator = $amount/$days_diff;
	return $calculator;
}
 function process_target_weekly($start,$end,$amount){
	
	$diff = cal_days($start,$end);
	$module = $diff / 7;
	$weeks = explode('.',$module);
	$calculator = $amount/$weeks[0];
	return $calculator;
}

 function process_target_monthly($start,$end,$amount){
	 
	$diff = cal_days($start,$end);
	if($diff >= 30){
    	$module = $diff / 30;
    	$weeks = explode('.',$module);
    	$calculator = $amount/$weeks[0];
    	return $calculator;
	}else{
	    return 0;
	}

}
function get_interest_amount($percentage,$target_amount){
	
	$interest = $percentage * $target_amount / 100;
	return $interest;
}
function load_directory($file_name){
/*	
	$path =  scandir(realpath("../app/logs/$file_name/"));
	foreach ($path as $key =>$value){
		$list[] = $value;
	}
	if (in_array($file_name,$list)){
		return true;
	} */
}
function image_helper($email,$path){
	
	//$path = 'logs/alfadaguru@gmail.com/e6ccb4cb2ba63ea96bb647f97bce889c.jpeg';
	if (strpos($path,'/')){
		return 'yes';
	}
}
function display_image($path){
	$real_path =  'http://localhost/app/app/'.$path;
	return $real_path;
}
