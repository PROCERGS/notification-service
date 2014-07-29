<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity\LoadNotificationData;

class NotificationControllerTest extends WebTestCase
{

    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testJsonPostPageAction()
    {
        $this->client = static::createClient();
        $this->client->request(
                'POST', '/api/v1/notifications.json', array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"title":"title1","shortText":"shortText1","text":"text1","level":1,"receiver":"receiver1","sender":"sender1"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostNotificationActionShouldReturn400WithBadParameters()
    {
        $this->client = static::createClient();
        $this->client->request(
                'POST', '/api/v1/notifications.json', array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"ninja":"turtles"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    protected function assertJsonResponse($response, $statusCode = 200,
                                          $checkValidJson = true,
                                          $contentType = 'application/json')
    {
        $this->assertEquals(
                $statusCode, $response->getStatusCode(), $response->getContent()
        );
        $this->assertTrue(
                $response->headers->contains('Content-Type', $contentType),
                $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(
                    ($decode !== null && $decode !== false),
                    'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

}
