# Unstoppable Domains PHP API Client

PHP package for the [Unstoppable Domains](https://unstoppabledomains.com) API.

Please note the following: only the 'Stripe checkout flow' is implemented for now. This means you can only order domains if you have the Stripe payment type enabled.

## Getting Started

Run the following command to install this package into your project.

```
composer require coinrequest/unstoppable-domains-php-api 
```

### Prerequisites

You will need Composer to install this package.

### Installing

After installing this package with Composer, create a new Unstoppable Domains
instance. And include your Reseller ID and API key. Please contact Unstoppable Domains to obtain your private credentials.

Something like this:

```
$unstoppableDomains = new UnstoppableDomains('yourresellerid', 'yourpersonalapikey');
```

And call the desired endpoint

```
$unstoppableDomains->domainsEndpoint()->getDomain($domainName);
```

The current implemented endpoints are: 

* GET   /domains/$domainName
* GET   /users/$emailAddress
* POST  /users/$emailAddress/orders (STRIPE ONLY)
* GET   /users/$emailAddress/orders/$orderId

Documentation of the endpoints will be available online at a later moment. Please check the code for documentation and example requests and responses.
You can always check the docs and examples on the [Unstoppable Domains docs website](https://docs.unstoppabledomains.com).

## Running the tests

First, create a .env file and set the following values: 

* UNSTOPPABLE_DOMAINS_TEST_RESELLER_ID:  A valid UD Reseller ID. Preferably a test Reseller ID
* UNSTOPPABLE_DOMAINS_TEST_API_KEY    :  A valid UD API Key. Preferably a test API key
* STRIPE_TEST_API_KEY                 :  A test (<- TEST) Stripe API key. Probably starts with 'pk_test_'
* ORDER_CONFIRMATION_EMAIL_ADDRESS    :  An email address. If you want to receive the confirmation email address, please use your own email address.

Please see the .env.example for the template.

Run the tests in the Tests directory with PHPUnit.


## Built With

* [Unstoppable Domains](https://unstoppabledomains.com) - For the API Server
* [PHPUnit](https://github.com/sebastianbergmann/phpunit/) - Test Framework
* [Guzzle](https://github.com/guzzle/guzzle) - For HTTP Requests

## Contributing

Please help us to develop this package. Every input and/or feedback is really appreciated!

## License

This project is licensed under the MIT License.


