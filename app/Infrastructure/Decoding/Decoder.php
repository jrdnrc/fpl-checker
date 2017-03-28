<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Decoding;


/**
 * Class Base64StringDecoder
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
interface Decoder
{
    public function decode(string $encoded): string;
}