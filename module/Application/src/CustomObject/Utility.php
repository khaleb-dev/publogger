<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\CustomObject;

class Utility
{
	
	#application variables
	#{
		const APPLICATION_TITLE 		= 	'Publogger';

		const APPLICATION_NAME			=	'Publogger';

		const APPLICATION_DOMAIN		=	'https://publogger.khaleb.dev/';
		
	#}

	public function __construct()
	{
		# code...
	}

	public static function Generate_Random_Token()
	{
		$string = md5(uniqid(rand(), true));

		return $string;
	}

	public static function createSalt()
	{
		$string = md5(uniqid(rand(), true));

		return substr($string, 0, 3);
	}

	public static function randomStrings($length_of_string)
	{
		$str_result = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz';

		return substr(str_shuffle($str_result), 0, $length_of_string); 
	}

	public static function sanitize($data, $to_lowercase = false)
	{
		$str =  ($to_lowercase)? strtolower(strip_tags($data)) : strip_tags($data);
		
		$str = str_replace('<', '-', $str);
		$str = str_replace('>', '-', $str);
		$str = str_replace('</', '-', $str);
		$str = str_replace('javascript', '-', $str);
		$str = str_replace('script', '-', $str);
		
		return $str;
	}

	public static function generateBarcode($data, $width =180, $height=80)
	{
		$service = 'https://barcode.tec-it.com/barcode.ashx?data='.$data.'&code=Code128&dpi=96&dataseparator=';

		return '<img src="'.$service.'" width='.$width.' height='.$height.' alt="Barcode By TEC-IT" />';
	}

    public static function sendMail($to, $subject, $message)
	{
		$to      	= $to;
		$subject 	= $subject;
		$headers	= "MIME-Version: 1.0" . "\r\n";
		$headers   .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers   .= 'Reply-To: info@publogger.khaleb.dev' . "\r\n";
		$headers   .= 'From: <info@publogger.khaleb.dev>' . "\r\n";
		$headers   .= 'X-Mailer: PHP/' . phpversion();

		$message = $message;		

		mail($to, $subject, $message, $headers);

		return true;
	}

	public static function logErrorMsg($code, $message, $file, $line){
		$msg = "------------------------------------------------\n";
		$msg .= 'Error occured in: ' . $file . ' at line: ' . $line . ', error code '  . "{". $code . "}" . ', error msg: ' . "{" . $message . "}" . "\n";
		//$msg .= $trace . "\n";
		error_log($msg);
	}
	
	public static function logDBErrorMsg($message, $err_code, $file, $line){
		$msg = "------------------------------------------------\n";
		$msg .= 'Error occured in: ' . $file . ' at line: ' . $line . ', error code '  . "{". $err_code . "}" . ', error msg: ' . "{" . $message . "}" . "\n";
		error_log($msg);
	}

	public static function validateInt($intVariable)
	{
		if ($intVariable != '' && is_int($intVariable))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function validateFloat($floatVariable)
	{
		if ($floatVariable != '' && is_float($floatVariable))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function validateString($stringVariable)
	{
		if ($stringVariable != '' && is_string($stringVariable))
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	public static function validateBoolean($boolVariable)
	{
		if ($boolVariable != '' && is_bool($boolVariable))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function validateEmail($emailVariable)
	{
		if ($emailVariable != '' && filter_var($emailVariable, FILTER_VALIDATE_EMAIL))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function validateUsername($usernameVariable)
	{
		if ($usernameVariable != '' && preg_match("/^[a-zA-Z0-9]*$/", $usernameVariable))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function validateUrl($urlVariable)
	{
		if ($urlVariable != '' && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $urlVariable))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public static function wordCount($string, $word_limit = 10)
    {
        $words = explode(" ",$string);
        return implode(" ",array_splice($words,0,$word_limit));
    }

    public static function convertStringToSlug($string)
    {
    	$string = str_replace(' ', '-', $string);
    	return $string;
    }
	
	public static function getPercentage($amount, $percent, $approximate = 2)
	{
		// to avoid division by zero error, we'll first check if the total is zero
		if ($amount > 0) {
			return round($percent * ($amount / 100), $approximate);
		} 
		else {
			return 0;
		}
	}

    /**
     *	This function will convert timestamp and display it in pastime format e.g 1hr ago.
     */
	//date_default_timezone_set('America/New_York');  
	//echo xTimeAgo('2016-03-11 04:58:00');  
	public static function xTimeAgo($timestamp)  
	{  
		$time_ago			= strtotime($timestamp);  
		$current_time		= time();  
		$time_difference	= $current_time - $time_ago;  
		$seconds			= $time_difference;  
		$minutes			= round($seconds / 60 );      // value 60 is seconds  
		$hours				= round($seconds / 3600);     // value 3600 is 60 minutes * 60 sec  
		$days				= round($seconds / 86400);    // 86400 = 24 * 60 * 60;  
		$weeks				= round($seconds / 604800);   // 7*24*60*60;  
		$months				= round($seconds / 2629440);  // ((365+365+365+365+366)/5/12)*24*60*60  
		$years				= round($seconds / 31553280); // (365+365+365+365+366)/5 * 24 * 60 * 60  
		if($seconds <= 60)  
		{
			return "Just Now";
		}
		else if($minutes<=60)
		{
			if($minutes==1)
			{
				return "one minute ago";
			}
			else
			{
				return "$minutes minutes ago";
			}
		}
		else if($hours<=24)
		{
			if($hours==1)
			{
				return "an hour ago";
			}
			else  
			{
				return "$hours hrs ago";
			}
		}  
		else if($days<=7)
		{
			if($days==1)
			{
				return "yesterday";
			}
			else
			{
				return "$days days ago";
			}
		}
		else if($weeks <= 4.3) //4.3 == 52/12
		{  
			if($weeks==1)
			{
				return "a week ago";
			}
			else
			{
				return "$weeks weeks ago";
			}
		}
		else if($months<=12)
		{
			if($months==1)
			{
				return "a month ago";
			}
			else
			{
				return "$months months ago";
			}
		}
		else
		{
			if($years==1)
			{
				return "one year ago";
			}
				else
			{
				return "$years years ago";
			}
		}
	} 
}