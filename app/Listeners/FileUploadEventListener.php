<?php

namespace App\Listeners;

use App\Events\FileUploadEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Employee;
use Excel;
use App\Mail\ExcelImportNotification;
use Mail;
use App\Libraries\FileUpload;

class FileUploadEventListener
{
    public $excelFile; 

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  FileUploadEvent  $event
     * @return void
     */
    public function handle(FileUploadEvent $event)
    {
        $excelFile = $event->excelFile;

        $rejectedRowsCount = 0;
        $acceptedRowsCount = 0;

        $uploadPath = FileUpload::upload($excelFile);

        $retrievalChunk = 1000;

        if ($uploadPath) {
            Excel::filter('chunk')->load($uploadPath)->chunk($retrievalChunk, function($results) use (&$rejectedRowsCount, &$acceptedRowsCount) {

                $rowsToInsert = $results->toArray();

                foreach ($rowsToInsert as $key => $item) {
                    $itemValues = array_values($item);

                    //MUST handle if that employer already exists depending on business logic

                    if (in_array('', $itemValues) || in_array(null, $itemValues)) {
                        $rejectedRowsCount++;
                        unset($rowsToInsert[$key]);
                        continue;
                    }

                    // you can skip this step if you are sure that data come from sheet are solid
                    $rowsToInsert[$key] = [
                        'id' => $item ['uid'],
                        'first_name' => $item ['first_name'],
                        'second_name' => $item ['second_name'],
                        'family_name' => $item ['family_name'],
                    ];

                }

                Employee::insert($rowsToInsert);

                $acceptedRowsCount = count($results) - $rejectedRowsCount;
            }, false);

            $fileUploadStatistics = ['accepted' => $acceptedRowsCount, 'rejected' => $rejectedRowsCount];

            Mail::send(new ExcelImportNotification($fileUploadStatistics));
        }
    }
}

