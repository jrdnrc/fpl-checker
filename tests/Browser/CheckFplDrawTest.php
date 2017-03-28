<?php declare(strict_types = 1);

namespace JrdnRc\FplChecker\Tests\Browser;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Illuminate\Contracts\Filesystem\Filesystem;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\Base64StringDecoder;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\FplUrlDecoder;
use JrdnRc\FplChecker\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class CheckFplDrawTest extends DuskTestCase
{
    /** @var Filesystem */
    private $filesystem;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = $this->app->make(Filesystem::class);
    }

    /**
     * @test
     * @return void
     */
    public function check_bonus() : void
    {
        $url = $this->decodeExampleEmail();

        $this->browse(
            function (Browser $browser) use ($url) {
                $page = $browser->visit($url);
                $bonus = $this->stripWhitespace($page->text('.bonus-ticket'));

                if (env('POSTCODE') == $bonus) {
                    echo "You have won the bonus!\n";
                } else {
                    echo "You have not won the bonus draw\n";
                }
            }
        );
    }

    /**
     * A basic browser test example.
     *
     * @test
     * @return void
     */
    public function check_stackpot() : void
    {
        $url = $this->decodeExampleEmail();

        $this->browse(
            function (Browser $browser) use ($url) {
                $page = $browser->visit($url)->clickLink('Stackpot');

                $postcodes = array_map(
                    function (RemoteWebElement $element) {
                        return $this->stripWhitespace($element->getText());
                    },
                    $page->elements('.postcode.result-text')
                );

                if (in_array(env('POSTCODE'), $postcodes)) {
                    echo "You have won the stackpot!\n";
                } else {
                    echo "You have not won the stackpot\n";
                }
            }
        );
    }

    /**
     * @param string $str
     * @return string
     */
    protected function stripWhitespace(string $str) : string
    {
        return preg_replace('/\s+/', '', $str);
    }

    /**
     * @return string
     */
    protected function decodeExampleEmail() : string
    {
        $base64decoder = new Base64StringDecoder;
        $fplUrlDecoder = new FplUrlDecoder;

        $decoded = $base64decoder->decode($this->filesystem->get('tests/reminder_email.txt'));

        return $fplUrlDecoder->decode($decoded);
    }
}
