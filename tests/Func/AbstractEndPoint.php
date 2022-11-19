<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];

    protected function getResponseFromRequest(string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();
        $client->request($method, $uri, [], [], $this->serverInformations, $payload);

        return $client->getResponse();
    }

}