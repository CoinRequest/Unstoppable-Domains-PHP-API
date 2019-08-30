<?php

namespace UnstoppableDomains\Endpoints;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DomainsEndpoint extends BaseEndpoint
{


    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->endpoint = 'domains/';
    }


    /**
     * Gets Domain info for an .zil domain.
     *
     * Example Success Response structure:
     * {
     *   "domain": {
     *      "name": "bogdantest.zil",
     *      "owner": "0xa823a39d2d5d2b981a10ca8f0516e6eaff78bdcf",
     *      "reselling": null,
     *      "auction": null
     *    }
     * }
     *
     * @param string $domain Valid .zil domain
     *
     * @return array
     * @throws GuzzleException
     */
    public function getDomain($domain)
    {
        return $this->get($this->endpoint.$domain, null);
    }
}