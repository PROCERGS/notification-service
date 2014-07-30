<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity\LoadNotificationData;
use PROCERGS\NotificationServiceBundle\Entity\Notification;

class NotificationControllerTest extends WebTestCase
{

    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testJsonPostNotificationAction()
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

    public function testJsonPutNotificationActionShouldModify()
    {
        $this->client = static::createClient();
        $fixtures = array('PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity\LoadNotificationData');
        $this->customSetUp($fixtures);
        $notifications = LoadNotificationData::$notifications;
        $notification = array_pop($notifications);

        $url = sprintf('http://localhost/api/v1/notifications/%d.json', $notification->getId());

        $this->client->request('GET', $url,
                array('ACCEPT' => 'application/json'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),
                $this->client->getResponse()->getContent());

        $this->client->request(
                'PUT', $url, array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"title":"title2","shortText":"shortText2","text":"text2","level":1,"receiver":"receiver1","sender":"sender1"}'
        );

        //$this->assertJsonResponse($this->client->getResponse(), 204, false);

        $this->assertEquals(
                204, $this->client->getResponse()->getStatusCode(),
                $this->client->getResponse()->getContent()
        );

        $this->assertTrue(
                $this->client->getResponse()->headers->contains(
                        'Location', $url
                ), $this->client->getResponse()->headers
        );
    }

    public function testJsonPutNotificationActionShouldCreate()
    {
        $this->client = static::createClient();
        $id = 0;
        $this->client->request('GET',
                sprintf('/api/v1/notifications/%d.json', $id),
                array('ACCEPT' => 'application/json'));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode(),
                $this->client->getResponse()->getContent());

        $notification = $this->getRandomNotificationJSON();

        $this->client->request(
                'PUT', sprintf('/api/v1/notifications/%d.json', $id), array(),
                array(), array('CONTENT_TYPE' => 'application/json'),
                $notification
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
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

    protected function getRandomNotificationJSON()
    {
        $r = rand(0, 100);
        $notification = array(
            'title' => "title$r",
            'shortText' => "shortText$r",
            'text' => "text$r",
            'level' => rand(1, 3),
            'receiver' => "receiver$r",
            'sender' => "sender$r"
        );
        return json_encode($notification);
    }

}
