<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Google\Gmail;

use Exception;
use Google_Service_Gmail as Gmail;
use Google_Service_Gmail_ListMessagesResponse as MessagesResponse;
use Google_Service_Gmail_Message as Message;
use Illuminate\Contracts\Auth\Guard;
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

    /**
     * GmailClient constructor.
     *
     * @param Gmail $api
     * @param Guard $auth
     */
    public function __construct(Gmail $api, Guard $auth)
    {
        $this->api = $api;
        $this->auth = $auth;
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

        $messageBody = $this->decodeMessageBody($raw);

        $pattern = '/http:\/\/mailer.freepostcodelottery.com\/click.php\/.+\/.+\/\?reminder=[a-zA-Z0-9\-]+/';
        $matches = [];

        if (1 === preg_match($pattern, $messageBody, $matches)) {
            return array_shift($matches);     // Full URL
        }

        throw new Exception('No url could be found.');
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

    /**
     * @param string $raw
     * @return string
     */
    private function decodeMessageBody(string $raw)
    {
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $raw));    // GMail data is base64url-encoded

        return (new Parser)
            ->setText($decoded)
            ->getMessageBody('html');
    }
}