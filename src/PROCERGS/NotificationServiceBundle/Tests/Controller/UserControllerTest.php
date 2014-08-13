<?php

namespace PROCERGS\NotificationServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity\LoadUserData;
use PROCERGS\NotificationServiceBundle\Entity\User;

class UserControllerTest extends WebTestCase
{

    /** @var Symfony\Bundle\FrameworkBundle\Client * */
    private $client;

    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testJsonGetUserAction()
    {
        $fixtures = $this->getFixtures();
        $this->loadFixtures($fixtures);
        $users = LoadUserData::$users;
        $user = array_pop($users);

        $route = $this->getUrl('api_1_get_user',
                        array('id' => $user->getId(), '_format' => 'json')) . '?apikey=123';

        $this->client->request('GET', $route,
                array());
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    public function testHeadRoute()
    {
        $this->loadFixtures($this->getFixtures());
        $users = LoadUserData::$users;
        $user = array_pop($users);

        $url = sprintf('/api/v1/users/%d.json?apikey=123', $user->getId());

        $this->client->request('HEAD', $url,
                array());
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);
    }

    public function testNewUserAction()
    {
        $this->client->request(
                'GET', '/api/v1/users/new.json?apikey=123', array(), array()
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, true);
        $this->assertEquals(
                '{"children":{"id":1,"username":"admin","enabled":true,"roles":["ROLE_GET_NOTIFICATION","ROLE_LIST_NOTIFICATIONS","ROLE_API_V1","ROLE_ADMIN"],"api_keys":[{"id":1,"api_key":"123","issued_at":"2014-08-06T12:47:33-0300","enabled":true}]}}',
                $response->getContent(), $response->getContent()
        );
    }

    public function testJsonPostUserAction()
    {
        $this->client = static::createClient();
        $this->client->request(
                'POST', '/api/v1/users.json?apikey=123', array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"apikey":"123","id":1,"username":"admin","enabled":true,"roles":["ROLE_GET_NOTIFICATION","ROLE_LIST_NOTIFICATIONS","ROLE_API_V1","ROLE_ADMIN"],"api_keys":[{"id":1,"api_key":"123","issued_at":"2014-08-06T12:47:33-0300","enabled":true}]}'
        );
        if (strstr($this->client->getResponse()->getContent(), 'You do not have the necessary permissions') !== false) {
            die("Failed!");
        }
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPostUserActionShouldReturn400WithBadParameters()
    {
        $this->client = static::createClient();
        $this->client->request(
                'POST', '/api/v1/users.json?apikey=123', array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"ninja":"turtles"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }

    public function testJsonPutUserActionShouldModify()
    {
        $this->client = static::createClient();
        $fixtures = $this->getFixtures();
        $this->customSetUp($fixtures);
        $users = LoadUserData::$users;
        $user = array_pop($users);

        $url = sprintf('http://localhost/api/v1/users/%d.json?apikey=123',
                $user->getId());

        $this->client->request('GET', $url, array());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),
                $this->client->getResponse()->getContent());

        $this->client->request(
                'PUT', $url, array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"username":"admin2"}'
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

    public function testJsonPutUserActionShouldCreate()
    {
        $this->client = static::createClient();
        $id = 0;
        $this->client->request('GET', sprintf('/api/v1/users/%d.json', $id),
                array());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode(),
                $this->client->getResponse()->getContent());

        $user = $this->getRandomNotificationJSON();

        $this->client->request(
                'PUT', sprintf('/api/v1/users/%d.json', $id), array(), array(),
                array('CONTENT_TYPE' => 'application/json'), $user
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPatchUserAction()
    {
        $this->loadFixtures($this->getFixtures());
        $users = LoadUserData::$users;
        $user = array_pop($users);
        $url = sprintf('/api/v1/users/%d.json', $user->getId());

        $this->client->request(
                'PATCH', $url, array(), array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"text":"changed!"}'
        );
        $response = $this->client->getResponse();

        $this->assertEquals(
                204, $response->getStatusCode(), $response->getContent()
        );
        $this->assertTrue(
                $response->headers->contains(
                        'Location', "http://localhost$url"
                ), $response->headers
        );
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
        $user = array(
            'title' => "title$r",
            'shortText' => "shortText$r",
            'text' => "text$r",
            'level' => rand(1, 3),
            'receiver' => "receiver$r",
            'sender' => "sender$r"
        );
        return json_encode($user);
    }

    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'userpass',
        );

        $this->client = static::createClient(array(), $this->auth);
    }

    protected function getFixtures()
    {
        return array('PROCERGS\NotificationServiceBundle\Tests\Fixtures\Entity\LoadUserData');
    }

    private function logIn()
    {
        $this->client->getRequest()->attributes->add(array('apikey' => 123));
    }

}
