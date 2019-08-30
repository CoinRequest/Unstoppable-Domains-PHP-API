<?php

namespace UnstoppableDomains\Endpoints;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OrdersEndpoint extends BaseEndpoint
{

    public function __construct(Client $client)
    {
        parent::__construct($client);
        //main endpoint is something like: users/email@address.com/orders
        $this->endpoint = 'users/';
    }


    /**
     * Gets Order details for an Order Id.
     *
     * Blockchain status can be: PENDING, MINED, CANCELED
     *
     * Example Success Response structure:
     * {
     *    "order": {
     *    "orderNumber": "-Lm9wiYytgrpf4YCWYv6",
     *    "subtotal": 10,
     *    "items": [
     *      {
     *          "type": "ZNS_DOMAIN",
     *          "name": "reseller-test-udtesting-24287.zil",
     *          "test": true,
     *          "blockchain": {
     *              "status": "PENDING"
     *          }
     *      }
     *     ]
     *    }
     * }
     *
     * @param $emailAddress
     * @param $orderId
     *
     * @return array
     * @throws GuzzleException
     */
    public function getOrderFor($emailAddress, $orderId)
    {
        return $this->get($this->endpoint.$emailAddress.'/orders/'.$orderId, null);
    }


    /**
     * Creates an Order at Unstoppable Domains by Stripe Token
     *
     * When the User checks out via the Stripe Checkout, an Stripe Token will be returned.
     * Use that token for this method, to create an Order at Unstoppable Domains
     *
     * Example Request:
     * {
     *    "order":{
     *    "payment":{
     *          "type":"stripe",
     *          "tokenId":"tok_1FAeVFG8PQyZCUJhJp7emswP"
     *    },
     *    "domains":[
     *      {
     *          "name":"reseller-test-udtesting-17829.zil",
     *          "owner":"0xa823a39d2d5d2b981a10ca8f0516e6eaff78bdcf",
     *          "resolution":{
     *              "crypto":{
     *                  "ZIL":{
     *                      "address":"0xe568f2BB42A77F6508911290d581B3Af107b1e4B"
     *                  },
     *                  "ETH":{
     *                      "address":"0x20B4564DEB7AF89ece828d843D0Ac2c16934a23e"
     *                  }
     *              }
     *          }
     *      }
     *     ]
     *    }
     * }
     *
     * Example Response:
     *
     * {
     *    "order":{
     *          "orderNumber":"-Lmz2FnYCUZdVe_foJ2M",
     *          "subtotal":10,
     *          "test":true,
     *          "payment":{
     *              "type":"stripe"
     *          },
     *          "items":[
     *              {
     *                  "type":"ZNS_DOMAIN",
     *                  "name":"reseller-test-udtesting-17829.zil",
     *                  "blockchain":{
     *                      "status":"PENDING"
     *                  }
     *              }
     *          ]
     *     }
     * }
     *
     * @param string $stripeToken  Stripe Token. You will receive a Stripe Token when the User successfully checks out
     *                             via the Stripe Checkout Flow
     * @param string $emailAddress Valid email address
     * @param string $domain       Valid .zil domain
     * @param null   $owner        Valid Zilliqa Or Ethereum wallet address. Example: 0xa823a39d2d5d2b981a10ca8f0516e6eaff78bdcf
     * @param array  $addresses    Array of wallet addresses. Example : ["ZIL" =>
     *                             "0xe568f2BB42A77F6508911290d581B3Af107b1e4B", "ETH"=>
     *                             "0x20B4564DEB7AF89ece828d843D0Ac2c16934a23e"]
     *
     * @return array
     * @throws GuzzleException
     */
    public function orderDomainWithStripeFor($stripeToken, $emailAddress, $domain, $owner, $addresses = [])
    {

        if ( ! is_array($addresses)) {
            return $this->orderDomainWithStripeFor($stripeToken, $emailAddress, $domain, $owner, []);
        }

        $addressMapper = [];
        foreach ($addresses as $ticker => $address) {
            array_push($addressMapper, [$ticker => ['address' => $address]]);
        }

        $params = [
            'body' => json_encode([
                'order' => [
                    'payment' => [
                        'type'    => 'stripe',
                        'tokenId' => $stripeToken
                    ],
                    'domains' => [
                        [
                            'name'       => $domain,
                            'owner'      => $owner,
                            //"resolution" => [
                            //    "crypto" => [
                            //        $addressMapper
                            //    ]
                            //]
                        ]
                    ]
                ],
            ])
        ];

        return $this->post($this->endpoint.$emailAddress.'/orders', $params);
    }


    /**
     * Prepay an Order as a Reseller.
     *
     * @throws Exception
     */
    public function preOrderDomain()
    {
        throw new Exception('@preOrderDomain is not yet implemented!');
    }
}