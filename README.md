# PSR7 response sender

This library is a companion to the [`lib-router`](https://github.com/CodeIncHQ/lib-router) written in PHP 7. It provides a responder to stream [PSR7](https://www.php-fig.org/psr/psr-7/) [responses](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface) to a web browser. It provides a standard interface `ResponseSenderInterface` for PSR-7 reponse senders. A response sender is capable of sending anything implementing the PSR7 [`ResponseInterface`](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface).

The library also provides the `HttpVersionCheckMiddleware` middleware to insure the response will be sent using the same version of the HTTP protocol as the request.


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

This library is available through [Packagist](https://packagist.org/packages/codeinchq/lib-psr7responsesenders) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinchq/lib-psr7responsesenders
```


## Dependencies 

* [PHP 7.2](http://php.net/releases/7_2_0.php)
* [`psr/http-message`](https://packagist.org/packages/psr/http-message) for the standard PSR-7 objects interfaces ;
* [`psr/http-server-middleware`](https://packagist.org/packages/psr/http-server-middleware) for the PSR-15 middleware interface ;

**Recommended library:**
* the [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) library is strongly recommended to add cookies to the PSR7 responses.


## License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).


