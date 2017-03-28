<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Tests\Infrastructure\Google\Gmail;

use Google_Client;
use Google_Service_Gmail as Gmail;
use Illuminate\Contracts\Auth\Guard;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\Decoder;
use JrdnRc\FplChecker\Laravel\Infrastructure\Google\Gmail\GmailClient;
use JrdnRc\FplChecker\Tests\TestCase;
use JrdnRc\FplChecker\Tests\Util\CreatesGmailMessages;
use Psr\Log\NullLogger;

/**
 * Class GmailClientTest
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class GmailClientTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_should_extract_reminder_url() : void
    {
        $client = $this
            ->getMockBuilder(Google_Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->any())->method('getLogger')->willReturn(new NullLogger);

        $api  = new GmailApi($client);
        $auth = $this->getMockBuilder(Guard::class)->getMock();
        $auth->expects($this->exactly(2))->method('user')->willReturn(new User);

        $sut = new GmailClient($api, $auth, app(Decoder::class));
        $sut->findReminderUrl();
    }
}

class GmailApi extends Gmail
{
    public $serviceName = 'gmail';
    public function __construct(Google_Client $client)
    {
        parent::__construct($client);

        $this->users_messages = new MessagesApi(
            $this,
            $this->serviceName,
            'messages',
            array(
                'methods' => array(
                    'get'         => array(
                        'path'       => '{userId}/messages/{id}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'userId'          => array(
                                'location' => 'path',
                                'type'     => 'string',
                                'required' => true,
                            ),
                            'id'              => array(
                                'location' => 'path',
                                'type'     => 'string',
                                'required' => true,
                            ),
                            'format'          => array(
                                'location' => 'query',
                                'type'     => 'string',
                            ),
                            'metadataHeaders' => array(
                                'location' => 'query',
                                'type'     => 'string',
                                'repeated' => true,
                            ),
                        ),
                    ),
                    'list'        => array(
                        'path'       => '{userId}/messages',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'userId'           => array(
                                'location' => 'path',
                                'type'     => 'string',
                                'required' => true,
                            ),
                            'q'                => array(
                                'location' => 'query',
                                'type'     => 'string',
                            ),
                        ),
                    ),
                )
            )
        );
    }
}

class MessagesApi extends \Google_Service_Gmail_Resource_UsersMessages
{
    use CreatesGmailMessages;

    public function listUsersMessages($userId, $optParams = array())
    {
        $response = new \Google_Service_Gmail_ListMessagesResponse;

        $response->setMessages([
            $this->message('123'),
            $this->message('456'),
        ]);

        return $response;
    }

    public function get($userId, $id, $optParams = array())
    {
        return $this->message($id, file_get_contents(storage_path('app/tests/reminder_email.txt')));
    }
}

class User
{
    public $google_id = '1234567890';
}