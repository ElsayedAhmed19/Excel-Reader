<?php
namespace App\Libraries;

class FileUpload
{
	public static function upload($file, $fileSavedName = null, $fileSavedPath = null)
	{
		$fileName = $fileSavedName;
		$filePath = $fileSavedPath;

		if (!$fileSavedName) {
			$fileName = $file->getClientOriginalName();
			$fileExtension = $file->getClientOriginalExtension();
		}

		if (!$fileSavedPath) {
			//dummy value
			$filePath = 'excel_files';
		}

		$file->move($filePath, $fileName);

        return $filePath . "/" . $fileName;
	}
}