<?php

namespace App\Transport;

use Log;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MinimalLogTransport extends AbstractTransport
{
    public function __construct(EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null, string $channel = 'mail')
    {
        parent::__construct($dispatcher, $logger);
        $this->channel = $channel;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        Log::channel($this->channel)->info("{$email->getTo()[0]->getAddress()}: {$email->getSubject()}");
    }

    public function __toString(): string
    {
        return 'minimal-log';
    }
}