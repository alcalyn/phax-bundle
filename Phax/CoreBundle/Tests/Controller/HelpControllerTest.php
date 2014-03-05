<?php

namespace Phax\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelpControllerTest extends WebTestCase
{
    public function testDefault()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/default');
    }

}
