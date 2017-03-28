<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Google\Gmail;

use Exception;
use Google_Service_Gmail as Gmail;
use Google_Service_Gmail_ListMessagesResponse as MessagesResponse;
use Google_Service_Gmail_Message as Message;
use Illuminate\Contracts\Auth\Guard;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\Decoder;
use PhpMimeMailParser\Parser;

/**
 * Class GmailClient
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class GmailClient
{
    /** @var Gmail */
    private $api;
    /** @var Guard */
    private $auth;
    /** @var Decoder */
    private $decoder;

    /**
     * GmailClient constructor.
     *
     * @param Gmail   $api
     * @param Guard   $auth
     * @param Decoder $decoder
     */
    public function __construct(Gmail $api, Guard $auth, Decoder $decoder)
    {
        $this->api = $api;
        $this->auth = $auth;
        $this->decoder = $decoder;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function findReminderUrl(): string
    {
        /** @var Message $message */
        $message = $this->scanForMessages()->getMessages()[0];

        $raw = $this->getRawMessageData($message->getId());

        return $this->decoder->decode($raw);
    }

    /**
     * @return MessagesResponse
     */
    private function scanForMessages() : MessagesResponse
    {
        return $this->api->users_messages->listUsersMessages(
            $this->auth->user()->google_id,
            [
                'q' => 'from:chris@freepostcodelottery.com draw alert',
            ]
        );
    }

    /**
     * @param string $messageId
     * @return string
     */
    private function getRawMessageData(string $messageId) : string
    {
        $message = $this->api->users_messages->get(
            $this->auth->user()->google_id,
            $messageId,
            [
                'format' => 'raw',
            ]
        );

        return $message->raw;
    }
}