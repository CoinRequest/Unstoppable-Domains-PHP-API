<?php

namespace UnstoppableDomains\Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UsersEndpoint extends BaseEndpoint
{

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->endpoint = 'users/';
    }


    /**
     * Gets User info for an email address.
     *
     * Example Success Response structure:
     * {
     *   "user": {
     *      "email": "bogdan@unstoppabledomains.com",
     *      "registered": true,
     *      "ownsDomain": true,
     *      "eligibleForFreeDomain": false
     *   }
     * }
     *
     * @param string $emailAddress Valid email address
     *
     * @return array
     * @throws GuzzleException
     */
    public function getUserByEmail($emailAddress)
    {
        return $this->get($this->endpoint.$emailAddress, null);
    }
}