<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Decoding;

/**
 * Class DecoderPool
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class DecoderPool implements Decoder
{
    /** @var Decoder[] */
    private $decoders;

    /**
     * DecoderPool constructor.
     *
     * @param Decoder[] $decoders
     */
    public function __construct(Decoder ...$decoders)
    {
        $this->decoders = $decoders;
    }

    /**
     * @param string $encoded
     * @return string
     */
    public function decode(string $encoded): string
    {
        foreach ($this->decoders as $decoder) {
            $encoded = $decoder->decode($encoded);
        }

        return $encoded;
    }
}