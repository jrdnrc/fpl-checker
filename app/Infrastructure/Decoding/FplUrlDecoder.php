<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Decoding;

use InvalidArgumentException;

/**
 * Class FplUrlDecoder
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class FplUrlDecoder implements Decoder
{

    /**
     * @param string $encoded
     * @return string
     * @throws InvalidArgumentException
     */
    public function decode(string $encoded): string
    {
        $pattern = '/http:\/\/mailer.freepostcodelottery.com\/click.php\/.+\/.+\/\?reminder=[a-zA-Z0-9\-]+/';
        $matches = [];

        if (1 === preg_match($pattern, $encoded, $matches)) {
            return array_shift($matches);     // Full URL
        }

        throw new InvalidArgumentException('Could not find FPL URL in encoded data');
    }
}