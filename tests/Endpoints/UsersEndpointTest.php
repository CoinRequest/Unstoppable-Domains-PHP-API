<?php

namespace UnstoppableDomains\Tests\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use UnstoppableDomains\Tests\TestCase;

class UsersEndpointTest extends TestCase
{

    /**
     * @test
     */
    function it_should_be_able_to_get_the_info_for_an_existing_user()
    {
        $email = 'bogdan@unstoppabledomains.com';

        try {
            $response = $this->unstoppableDomains->usersEndpoint()->getUserByEmail($email);
            $this->assertEquals($email, $response['user']->email);
            $this->assertTrue($response['user']->registered);
            $this->assertFalse($response['user']->eligibleForFreeDomain);

        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }
    }


    /**
     * @test
     */
    function it_should_be_able_to_get_the_info_for_an_unregistered_user()
    {
        $email = 'notregisterd@coinrequest.io';

        try {
            $response = $this->unstoppableDomains->usersEndpoint()->getUserByEmail($email);
            $this->assertEquals($email, $response['user']->email);
            $this->assertFalse($response['user']->registered);
            $this->assertFalse($response['user']->eligibleForFreeDomain);

        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }
    }
}