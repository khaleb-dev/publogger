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
        	$errors['message'] = "We encountered an error while processing your request. It maybe that your file is too large, the file extension is not supported, or attempt to force upload for an empty file.";
    	}

    	if(empty($errors)) {
			$fileNewName = Utility::randomStrings(8).time().Utility::randomStrings(4).$acceptedParams['type'].".".$fileExt;

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
			$response['message'] = $errors['message'];
			$response['fileUrl'] = '';

			return $response;
		}
	}

	public static function delete($path, $fileName)
	{
		if ($path !== '' && $fileName !== '') {
			$file = $path.$fileName;
			try {
				unlink($file);

				return true;
			} 
			catch (\Throwable $th) {
				return $th;
			}
		}
	}
}
