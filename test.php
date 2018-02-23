<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     06/12/2017
// Time:     19:00
// Project:  lib-gui
//
require_once __DIR__.'/vendor/autoload.php';

$router = new \CodeInc\Router\Router();
$router->addRoute("/", new \CodeInc\Router\RequestHandlers\CallableRequestHandler(function(\Psr\Http\Message\ServerRequestInterface $serverRequest):\Psr\Http\Message\ResponseInterface {
	return new \GuzzleHttp\Psr7\Response(200, [], "Hello!");
}));

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
$handler = $router->getRequestHandler(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
$response = $router->process($request, $handler);

(new \CodeInc\PSR7ResponseSender\SimpleResponseSender())->send($response, $request);
