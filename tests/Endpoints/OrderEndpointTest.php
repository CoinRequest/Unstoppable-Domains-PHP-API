<?php

namespace UnstoppableDomains\Tests\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use UnstoppableDomains\Tests\TestCase;

class OrderEndpointTest extends TestCase
{

    /**
     * @test
     */
    function it_should_be_able_to_get_the_info_for_an_order()
    {
        $email = 'paul@coinrequest.io';
        $orderId = '-LnTKhB01cyIQ8whr8nm';

        try {
            $response = $this->unstoppableDomains->ordersEndpoint()->getOrderFor($email, $orderId);
            $this->assertEquals($orderId, $response['order']->orderNumber);
            $this->assertTrue(true, $response['order']->test);
            $this->assertEquals('stripe', $response['order']->payment->type);
            $this->assertEquals('success',$response['order']->payment->status);


        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }
    }


    /**
     * @test
     */
    function it_should_be_able_to_place_an_stripe_order()
    {
        $email = getenv('ORDER_CONFIRMATION_EMAIL_ADDRESS');
        if ($email === false) {
            dd('Email address not set. Please your email address in the .env file for a confirmation email.');
        }
        $stripeToken = $this->getStripeToken();
        $domainName = $this->getRandomTestDomain();
        $type = 'ETH';
        $publicKey = '0x04a2f646354d081019fa3197ad8eae554ffbd266172d84dee778a0e41eb4f7330d991bdb57bc5d65e0928b343bc71bc8cb68e4932ea1721d4c6445059702a17b5b';


        try {
            $response = $this->unstoppableDomains->ordersEndpoint()->orderDomainWithStripeFor($stripeToken, $email, $domainName, $type, $publicKey);
            $this->assertTrue(isset($response['order']->orderNumber));
            $this->assertTrue(isset($response['order']->subtotal));
            $this->assertTrue(($response['order']->subtotal > 0));
            $this->assertEquals($domainName, $response['order']->items[0]->name);
            $this->assertTrue(true, $response['order']->test);
            $this->assertEquals('stripe', $response['order']->payment->type);
            $this->assertTrue(isset($response['order']->items[0]->blockchain->status));
            $this->assertEquals('PENDING', $response['order']->items[0]->blockchain->status);


        } catch (GuzzleException $e) {
            $this->assertEquals(true, false);
        }

    }
}