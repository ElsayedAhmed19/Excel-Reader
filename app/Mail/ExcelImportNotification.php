<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExcelImportNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $fileUploadStatistics;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileUploadStatistics)
    {
        $this->fileUploadStatistics = $fileUploadStatistics;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // can be put inside constants file if needed
        $senderEmail = 'elsayed.ahmed.elwasefy@gmail.com';

        // can be retrieved from auth if needed
        $receiverEmail = 'elsayed.ahmed.elwasefy@gmail.com';

        $subject = "Excel File Import Statistics";
        
        $this->from($senderEmail, config('app.name'))
            ->to($receiverEmail)
            ->subject($subject)
            ->view('emails.excel_import_notification')
            ->with($this->fileUploadStatistics);

        return $this;
    }
}
