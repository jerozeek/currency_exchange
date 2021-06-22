<?php

	function random_string_generator($length = 20){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen($characters);
		$random_string = '';
		for($i = 0; $i < $length; $i++){
		$random_string .= $characters[rand(0,$characters_length - 1)];
		}
		return $random_string;
	}
	function random_number($length = 6){
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        $pin=$randomString; 
        return $pin;
	}
function referral_generator($length = 12){
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	$pin=$randomString;
	return $pin;
}

$otp_format = function ($otp){
    if (is_numeric($otp) && strlen($otp) === 4){
        return true;
    }
    return false;
};

function activation_code($length = 4){
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $pin=$randomString;
    return $pin;
}
	function hash_id(){
        $random = rand(00000000000,99999999999);
        $time = time();
        $md5 = hash('sha256',$time);
        $hash = password_hash($random,PASSWORD_BCRYPT);
        return $hash . $md5;
    }
	function password($password){
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}
	function generate_filter(){
		$hash = hash('sha256',generate_uniqid());
		return $hash;
	}
	function generate_uniqid(){
		return uniqid();
	}
	function RandomStringGenerator($n = 10)
	{
	// Variable which store final string
	$generated_string = "";
	// Create a string with the help of
	// small letters, capital letters and
	// digits.
	$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	// Find the lenght of created string
	$len = strlen($domain);
	// Loop to create random string
	for ($i = 0; $i < $n; $i++)
	{
		// Generate a random index to pick
		// characters
		$index = rand(0, $len - 1);
		// Concatenating the character
		// in resultant string
		$generated_string = $generated_string . $domain[$index];
	}
	// Return the random generated string
	return $generated_string;
}

function SetTimer(){
    $objDateTime = new DateTime('NOW');
    return $objDateTime->format('c');
}

function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
