<?php

namespace App\Service;

class Mail extends MailHelper
{
    public function send(string $email,string $subject,string $template,?array $data = [])
    {
        $email = $this->getEmail($email);

        // if not subscribed, 404 error
        if (!$email->getSubscribed()) {
            return 404;
        }

        // send mail
        $response = $this->sendMail($email, $subject, $template, $data);

        // send true or false
        return $response->message === 'OK';
    }

    public function multi(array $emails,string $subject,string $template,?array $data = [])
    {
        $responses = [];
        foreach($emails as $email) {
            $responses[$email] = $this->send(trim($email), $subject, $template, $data);
        }

        return $responses;
    }
}
