# PSR-7 response sender

This library is a companion to the [`lib-router`](https://github.com/CodeIncHQ/lib-router) written in PHP 7. It provides the `ResponseSender` responder to stream [PSR-7](https://www.php-fig.org/psr/psr-7/) [responses](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface) to a web browser and the interface `ResponseSenderInterface` for PSR-7 reponse senders. 

A response sender is capable of streaming anything implementing the PSR-7 [`ResponseInterface`](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface).

## Usage

```php
<?php
use CodeInc\PSR7ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\Response;

// a response can be anything implementing ResponseInterface, here the Guzzle implementation
$response = new Response();

// sends the response to the web browser
$sender = new ResponseSender();
$sender->send($response);
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/lib-psr7responsesender) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/lib-psr7responsesender
```


**Recommended library:**
* the [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) library is strongly recommended to add cookies to the PSR-7 responses.


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).


