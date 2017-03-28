<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Decoding;

/**
 * Class Base64StringDecoder
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class Base64StringDecoder implements Decoder
{
    /**
     * @param string $encoded
     * @return string
     */
    public function decode(string $encoded) : string
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $encoded));    // GMail data is base64url-encoded
    }
}