<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

declare(strict_types=1);

namespace Application\CustomObject;

use Application\CustomObject\Utility;

class CustomFileUpload
{
	function __construct()
	{
		# code...
	}

	public static function upload($data, $acceptedParams)
	{
		$errors = [];
		$response = [];
		$file = htmlentities(trim($data['file']['name']), ENT_QUOTES, 'UTF-8');
		$tmpDir = $data['file']['tmp_name'];
		$fileSize = $data['file']['size'];
		$fileExt = strtolower(pathinfo($file,PATHINFO_EXTENSION));

		if(!in_array($fileExt, $acceptedParams['exts'])) {
        	$errors['message'] = "You file is too large or extension not supported(.mp3 for audio or .mp4 for video.)";
    	}

    	if(empty($errors)) {
			$fileNewName = "coc_".Utility::randomStrings(3).time().Utility::randomStrings(2).$acceptedParams['type'].".".$fileExt;

			if (move_uploaded_file($tmpDir, $acceptedParams['path'].$fileNewName)) {

				$domainParts = explode(".",$_SERVER['SERVER_NAME']);

				if($domainParts[0] == 'admin'){
				    unset($domainParts[0]);
				}
				$domainName = count($domainParts) > 1 ? implode('.', $domainParts) : implode('', $domainParts);

				$response['code'] = 200;
				$response['status'] = "success";
				$response['message'] = "File uploaded!";
				$response['fileUrl'] = $domainName.'/i1m2a3g4e5s/'.$fileNewName;
				$response['fileName'] = $fileNewName;

				return $response;
			} else {
				$response['code'] = 502;
				$response['status'] = "error";
				$response['message'] = "upload failed for UNKNOWN reasons!";
				$response['fileUrl'] = '';

				return $response;
			}
		} else {
			$response['code'] = 407;
			$response['status'] = 'error';
			$response['message'] = $errors;
			$response['fileUrl'] = '';

			return $response;
		}
	}
}
