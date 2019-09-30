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
     *          "owner": {
     *              "type": "ETH",
     *              "publicKey:
     *              "0x04a2f646354d081019fa3197ad8eae554ffbd266172d84dee778a0e41eb4f7330d991bdb57bc5d65e0928b343bc71bc8cb68e4932ea1721d4c6445059702a17b5b"
     *          }
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
     * @param string $type         'ZIL' or 'ETH'
     * @param null   $publicKey    Valid Zilliqa Or Ethereum public key. Example:
     *                             0x04a2f646354d081019fa3197ad8eae554ffbd266172d84dee778a0e41eb4f7330d991bdb57bc5d65e0928b343bc71bc8cb68e4932ea1721d4c6445059702a17b5b
     * @param array  $addresses    Array of wallet addresses. Example : ["ZIL" =>
     *                             "0xe568f2BB42A77F6508911290d581B3Af107b1e4B", "ETH"=>
     *                             "0x20B4564DEB7AF89ece828d843D0Ac2c16934a23e"]
     *
     * @return array
     * @throws GuzzleException
     */
    public function orderDomainWithStripeFor($stripeToken, $emailAddress, $domain, $type, $publicKey, $addresses = [])
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
                            'name'  => $domain,
                            'owner' => [
                                'type'      => $type,
                                'publicKey' => $publicKey
                            ],
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