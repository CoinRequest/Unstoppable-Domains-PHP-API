<?php

namespace UnstoppableDomains;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use UnstoppableDomains\Endpoints\DomainsEndpoint;
use UnstoppableDomains\Endpoints\OrdersEndpoint;
use UnstoppableDomains\Endpoints\UsersEndpoint;

class UnstoppableDomains
{

    private $client;

    private $domainsEndpoint;

    private $usersEndpoint;

    private $ordersEndpoint;

    public static $API_ROUTE = 'https://unstoppabledomains.com/api/v1/resellers/';

    // Use 0 to wait indefinitely (the default behavior).
    public static $TIMEOUT = 0;

    public static $TEST_MODE = false;


    public function __construct($resellerId, $apiKey, $testMode = false)
    {
        self::$API_ROUTE .= $resellerId.'/';

        //not used
        self::$TEST_MODE = $testMode;

        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri'              => self::$API_ROUTE,
            // You can set any number of default request options.
            RequestOptions::TIMEOUT => self::$TIMEOUT,
            RequestOptions::VERIFY  => CaBundle::getBundledCaBundlePath(),
            'headers'               => [
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
                'Authentication' => 'Bearer '.$apiKey
            ]
        ]);
    }


    /**
     * @return DomainsEndpoint
     */
    public function domainsEndpoint(): DomainsEndpoint
    {
        if ( ! isset($this->domainsEndpoint) || is_null($this->domainsEndpoint)) {
            $this->setDomainsEndpoint(new DomainsEndpoint($this->client));
        }

        return $this->domainsEndpoint;
    }


    /**
     * @param DomainsEndpoint $domainsEndpoint
     */
    private function setDomainsEndpoint(DomainsEndpoint $domainsEndpoint)
    {
        $this->domainsEndpoint = $domainsEndpoint;
    }

    /**
     * @return UsersEndpoint
     */
    public function usersEndpoint(): UsersEndpoint
    {
        if ( ! isset($this->usersEndpoint) || is_null($this->usersEndpoint)) {
            $this->setUsersEndpoint(new UsersEndpoint($this->client));
        }

        return $this->usersEndpoint;
    }


    /**
     * @param UsersEndpoint $usersEndpoint
     */
    private function setUsersEndpoint(UsersEndpoint $usersEndpoint)
    {
        $this->usersEndpoint = $usersEndpoint;
    }

    /**
     * @return OrdersEndpoint
     */
    public function ordersEndpoint(): OrdersEndpoint
    {
        if ( ! isset($this->ordersEndpoint) || is_null($this->ordersEndpoint)) {
            $this->setOrdersEndpoint(new OrdersEndpoint($this->client));
        }

        return $this->ordersEndpoint;
    }


    /**
     * @param OrdersEndpoint $ordersEndpoint
     */
    private function setOrdersEndpoint(OrdersEndpoint $ordersEndpoint)
    {
        $this->ordersEndpoint = $ordersEndpoint;
    }
    
    

}