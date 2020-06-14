<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportedMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $successed;
    private array $failed;
    private $subject_for_mail;
    private $view_for_mail;
    private $headers_for_mail;

    public function __construct(array $successed, array $failed, string $view, string $headers, $subject)
    {
        $this->successed = $successed;
        $this->failed = $failed;
        $this->subject_for_mail = $subject;
        $this->view_for_mail = $view;
        $this->headers_for_mail = $headers;
    }

    public function build()
    {
        $mail = $this->subject($this->subject_for_mail)
            ->view($this->view_for_mail);

        $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
        $headers = __($this->headers_for_mail);

        if(count($this->successed) > 0) {
            $mail->attachData($bom . $headers. "\n" .join("\n", $this->successed), 'Успешные.csv');
        }

        if(count($this->failed) > 0) {
            $mail->attachData($bom . $headers . "\n" . join("\n", $this->failed), 'Ошибки.csv');
        }

        return $mail;
    }
}
