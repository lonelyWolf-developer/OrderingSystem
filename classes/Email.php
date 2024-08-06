<?php

class Email{
    public $recipient;
    public $subject;
    public $message;

    function sentEmail($email){
        $email->recipient = 'spravce@vlksamotar.cz';

        mail($email->recipient, $email->subject, $email->message);
    }
}

?>