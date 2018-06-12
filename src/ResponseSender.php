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
use CodeInc\HttpReasonPhraseLookup\HttpReasonPhraseLookup;
use Psr\Http\Message\ResponseInterface;


/**
 * Class ResponseSender
 *
 * @package CodeInc\Psr7ResponseSender
 * @author  Joan Fabrégat <joan@codeinc.fr>
 * @link https://github.com/CodeIncHQ/Psr7ResponseSender
 * @license MIT <https://github.com/CodeIncHQ/Psr7ResponseSender/blob/master/LICENSE>
 */
class ResponseSender implements ResponseSenderInterface
{
    /**
     * @var bool
     */
    private $removePhpHttpHeaders;

    /**
     * ResponseSender constructor.
     *
     * @param bool $removePhpHttpHeaders
     */
    public function __construct(bool $removePhpHttpHeaders = true)
    {
        $this->removePhpHttpHeaders = $removePhpHttpHeaders;
    }

    /**
     * Removes all the native PHP HTTP headers. Only sends the headers included in the PSR-7 response object.
     */
    public function removePhpHttpHeaders():void
    {
        $this->removePhpHttpHeaders = true;
    }

    /**
     * Sends all the native PHP HTTP headers and headers included in the PSR-7 response object.
     */
    public function sendPhpHttpHeaders():void
    {
        $this->removePhpHttpHeaders = false;
    }

    /**
     * @inheritdoc
     * @throws ResponsSenderException
     */
    public function send(ResponseInterface $response):void
    {
        $this->sendResponseHttpHeaders($response);
        $this->sendResponseBody($response);
    }

    /**
     * Sends the response HTTP headers.
     *
     * @param ResponseInterface $response
     * @throws ResponsSenderException
     */
    public function sendResponseHttpHeaders(ResponseInterface $response):void
    {
        // checking
        if (headers_sent()) {
            throw new ResponsSenderException("A response has already been sent to the web browser",
                $this);
        }

        // removing PHP native HTTP headers (if enabled)
        if ($this->removePhpHttpHeaders) {
            foreach (headers_list() as $header) {
                header_remove(explode(":", $header)[0]);
            }
        }

        // sending response HTTP headers
        $this->sendHttpVersionHeader(
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
        $this->sendHttpHeaders($this->listResponseHeaders($response));
    }

    /**
     * Returns the list of all the headers of a PSR-7 response.
     *
     * @param ResponseInterface $response
     * @return \Generator
     */
    protected function listResponseHeaders(ResponseInterface $response):\Generator
    {
        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                yield $header => $value;
            }
        }
    }

    /**
     * Sends the HTTP version header.
     *
     * @param string $protocolVersion
     * @param int $statusCode
     * @param null|string $reasonPhrase
     */
    protected function sendHttpVersionHeader(string $protocolVersion, int $statusCode,
        ?string $reasonPhrase = null):void
    {
        header(
            sprintf("HTTP/%s %d %s",
                $protocolVersion,
                $statusCode,
                $reasonPhrase ?? HttpReasonPhraseLookup::getReasonPhrase($statusCode)),
            true
        );
    }

    /**
     * Sends multiple HTTP headers.
     *
     * @param iterable $headers
     */
    protected function sendHttpHeaders(iterable $headers):void
    {
        foreach ($headers as $header => $values) {
            foreach ($values as $value) {
                header("$header: $value", false);
            }
        }
    }

    /**
     * Sends a response's body.
     *
     * @param ResponseInterface $response
     */
    public function sendResponseBody(ResponseInterface $response):void
    {
        if (($body = $response->getBody()->detach()) === null) {
            $body = $response->getBody()->__toString();
        }
        $this->sendBody($body);
    }

    /**
     * Sends the body.
     *
     * @param $body
     */
    protected function sendBody($body):void
    {
        if (is_resource($body)) {
            fpassthru($body);
            fclose($body);
        }
        else {
            echo $body;
        }
    }

}