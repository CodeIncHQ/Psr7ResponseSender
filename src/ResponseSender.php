<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - 2018 - Code Inc. SAS - All Rights Reserved.    |
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
// Date:     23/02/2018
// Time:     13:44
// Project:  Psr7ResponseSender
//
declare(strict_types = 1);
namespace CodeInc\Psr7ResponseSender;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseSender
 *
 * @package CodeInc\Psr7ResponseSender
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseSender implements ResponseSenderInterface
{
    /**
     * @var bool
     */
    private $removeNativeHttpHeaders;

    /**
     * ResponseSender constructor.
     *
     * @param bool $removeNativeHttpHeaders
     */
    public function __construct(bool $removeNativeHttpHeaders = true)
    {
        $this->removeNativeHttpHeaders = $removeNativeHttpHeaders;
    }

    /**
     * @inheritdoc
     * @throws ResponsSenderException
     */
    public function send(ResponseInterface $response):void
    {
        // checking
        if (headers_sent()) {
            throw new ResponsSenderException("A response has already been sent to the web browser",
                $this);
        }

        // sending
        if ($this->removeNativeHttpHeaders) {
            $this->remoteNativeHttpHeaders();
        }
        $this->sendResponseHttpHeaders($response);
        $this->sendResponseBody($response);
    }

    /**
     * Removes the native PHP HTTP headers.
     */
    private function remoteNativeHttpHeaders():void
    {
        foreach (headers_list() as $header) {
            header_remove(explode(":", $header)[0]);
        }
    }

    /**
     * Sends the response HTTP headers.
     *
     * @param ResponseInterface $response
     */
    private function sendResponseHttpHeaders(ResponseInterface $response):void
    {
        header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} "
            ."{$response->getReasonPhrase()}", true);
        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                header("$header: $value", false);
            }
        }
    }

    /**
     * Sends the response body.
     *
     * @param ResponseInterface $response
     */
    private function sendResponseBody(ResponseInterface $response):void
    {
        if (($resource = $response->getBody()->detach()) !== null) {
            fpassthru($resource);
            fclose($resource);
        }
        else {
            echo $response->getBody()->__toString();
        }
    }

}