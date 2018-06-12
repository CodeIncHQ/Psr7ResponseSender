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

`ResponseSender` constructor accepts two boolean parameters. The first enables or disables the transmission of the native PHP HTTP headers on the top of the headers included in the PSR-7 response object. The second enables or disables the GZ compression of the body (using [`ob_start()`](http://php.net/manual/function.ob-start.php) and [`ob_gzhanlder()`](http://php.net/manual/function.ob-gzhandler.php))   

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/psr7-response-sender) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/psr7-response-sender
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).


