<?php

namespace UnstoppableDomains\Tests\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use UnstoppableDomains\Tests\TestCase;

class DomainsEndpointTest extends TestCase
{

    /**
     * @test
     */
    function it_should_be_able_to_get_the_info_for_an_available_domain()
    {
        $domainName = $this->getRandomTestDomain();

        try {
            $response = $this->unstoppableDomains->domainsEndpoint()->getDomain($domainName);

            $this->assertFalse(isset($response['domain']->owner));
            $this->assertEquals($domainName,$response['domain']->name);
            $this->assertEquals(false,$response['domain']->reselling->availableForFree);
            $this->assertEquals(true,$response['domain']->reselling->test);
        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }

    }


    /**
     * @test
     */
    function it_should_return_the_owner_for_an_existing_domain()
    {
        $domainName = 'tyranids.zil';

        try {
            $response = $this->unstoppableDomains->domainsEndpoint()->getDomain($domainName);
            $this->assertTrue(isset($response['domain']->owner));
            $this->assertNotNull($response['domain']->owner);
        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }
    }


    /**
     * @test
     */
    function it_should_return_an_error_for_an_invalid_extension()
    {
        $domainName = $this->getTestDomain('wrong.extension');

        try {
            $response = $this->unstoppableDomains->domainsEndpoint()->getDomain($domainName);
            //should catch the error
        } catch (GuzzleException $e) {
            $this->assertEquals(400, $e->getCode());
        }
    }

    /**
     * @test
     */
    function it_should_return_an_error_for_an_invalid_domain()
    {
        $domainName = $this->getTestDomain('noextensiondomain');
        try {
            $response = $this->unstoppableDomains->domainsEndpoint()->getDomain($domainName);
            //should catch the error
        } catch (GuzzleException $e) {
            $this->assertEquals(400, $e->getCode());
        }
    }
}