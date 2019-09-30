<?php

namespace UnstoppableDomains\Tests;

use Composer\CaBundle\CaBundle;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use UnstoppableDomains\UnstoppableDomains;

class TestCase extends \PHPUnit\Framework\TestCase
{

    public $unstoppableDomains;


    protected function setUp(): void
    {
        parent::setUp();
        $dotEnv = Dotenv::create(dirname(__DIR__));
        $dotEnv->load();

        $resellerId = getenv('UNSTOPPABLE_DOMAINS_TEST_RESELLER_ID');
        if ($resellerId === false) {
            dd('Reseller ID not set. Please set the Reseller ID in the .env file.');
        }

        $apiKey = getenv('UNSTOPPABLE_DOMAINS_TEST_API_KEY');
        if ($apiKey === false) {
            dd('API Key not set. Please set the API Key in the .env file.');
        }

        $this->unstoppableDomains = new UnstoppableDomains($resellerId, $apiKey, true);
    }


    public function tearDown(): void
    {
        $this->unstoppableDomains = null;
    }


    public function getTestDomain($domainName)
    {
        $resellerId = getenv('UNSTOPPABLE_DOMAINS_TEST_RESELLER_ID');

        return 'reseller-test-'.$resellerId.'-'.$domainName;
    }


    public function getRandomTestDomain()
    {
        $randomNumber = time() + rand(1, 100);

        return $this->getTestDomain($randomNumber.'.zil');
    }


    public function getStripeToken()
    {
        //'card[number]=5555555555554444&card[cvc]=123&card[exp_month]=01&card[exp_year]=25&card[address_zip]=12345&key=pk_test_bERlHfGH5lT9rTIhKPg74H0o&pasted_fields=number' | jq -r '.id'`

        $stripeApiKey = getenv('STRIPE_TEST_API_KEY');
        if ($stripeApiKey === false) {
            dd('Stripe API Key not set. Please set the Stripe API Key in the .env file.');
        }

        if (strpos($stripeApiKey, 'live') !== false) {
            dd('Don\'t use a live API Key please.');
        }

        $client = new Client([
            'base_uri'              => 'https://api.stripe.com/v1/',
            RequestOptions::TIMEOUT => 10,
            RequestOptions::VERIFY  => CaBundle::getBundledCaBundlePath(),
            'headers'               => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$stripeApiKey
            ]
        ]);

        $params = [
            'form_params' => [
                'card' => [
                    'number'      => '4242424242424242',
                    'cvc'         => '123',
                    'exp_month'   => '12',
                    'exp_year'    => '25',
                    'address_zip' => '123456',
                ]
            ]
        ];

        try {
            $response = $client->request('POST', 'tokens', $params);

            $response = (array)json_decode($response->getBody());

            return $response['id'];
        } catch (GuzzleException $e) {
            return null;
        }
    }
}