<?php

class Email{
    public $recipient;
    public $subject;
    public $message;
    public $headers;

    function sentEmail($email){
        $email->recipient = 'spravce@vlksamotar.cz';
        $email->headers = "MIME-Version: 1.0\r\n" . "From: ordering.service@email.cz\r\n" . "Content-Type: text/plain; charset=utf-8\r\n";

        mail($email->recipient, $email->subject, $email->message, $email->headers);
    }
}

?>