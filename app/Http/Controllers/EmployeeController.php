<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\FileUploadEvent;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

	public function index()
	{
		return view('import');
	}

    public function uploadExcel(Request $request)
    {
        set_time_limit(0);
        ini_set('upload_max_filesize', '10M');

    	$excelFile = $request->file('excelFile');

        $fileExtension = $excelFile->getClientOriginalExtension();

        $errors = $this->validateExcelFile($fileExtension);

        if ($errors) {
        	return back()->withErrors($errors);
        }

    	event(new FileUploadEvent($excelFile));

    	echo json_encode(['status' => 'processing...']);
    }

    public function validateExcelFile($fileExtension)
    {
	    $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");

	    $errors = [];

	    if (!in_array($fileExtension, $extensions)) {
        	$errors['excelFile'] = 'File should be of type Excel';
	    }

	    return $errors;
    }
}
