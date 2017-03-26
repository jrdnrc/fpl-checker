<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Tests\Util;

/**
 * Trait CreatesGmailMessages
 *
 * @author jrdn hannah <jrdn@jrdnhannah.co.uk>
 */
trait CreatesGmailMessages
{
    /**
     * @param string $id
     * @param string $raw
     * @return \Google_Service_Gmail_Message
     */
    public function message(string $id, string $raw = null) : \Google_Service_Gmail_Message
    {
        $message = new \Google_Service_Gmail_Message;
        $message->setId($id);
        $message->setRaw($raw);

        return $message;
    }
}