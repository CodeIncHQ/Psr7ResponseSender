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
 * @author  Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseSender implements ResponseSenderInterface
{
    /**
     * @var bool
     */
    private $removePhpHeaders;

    /**
     * @var
     */
    private $enableGzCompression;

    /**
     * ResponseSender constructor.
     *
     * @param bool $removePhpHeaders
     * @param bool $enableGzCompression
     */
    public function __construct(bool $removePhpHeaders = true, bool $enableGzCompression = false)
    {
        $this->removePhpHeaders = $removePhpHeaders;
        $this->enableGzCompression = $enableGzCompression;
    }

    /**
     * Enables the GZ compression of the body.
     */
    public function enableGzCompression():void
    {
        $this->enableGzCompression = true;
    }

    /**
     * Disables the GZ compression of the body.
     */
    public function disableGzCompression():void
    {
        $this->enableGzCompression = false;
    }

    /**
     * Removes all the native PHP HTTP headers. Only sends the headers included in the PSR-7 response object.
     */
    public function removePhpHeaders():void
    {
        $this->removePhpHeaders = true;
    }

    /**
     * Sends all the native PHP HTTP headers and headers included in the PSR-7 response object.
     */
    public function sendPhpHeaders():void
    {
        $this->removePhpHeaders = false;
    }

    /**
     * @inheritdoc
     * @throws ResponsSenderException
     */
    public function send(ResponseInterface $response):void
    {
        $this->sendHttpHeaders($response);
        $this->sendBody($response);
    }

    /**
     * Sends the response HTTP headers.
     *
     * @param ResponseInterface $response
     * @throws ResponsSenderException
     */
    public function sendHttpHeaders(ResponseInterface $response):void
    {
        // checking
        if (headers_sent()) {
            throw new ResponsSenderException("A response has already been sent to the web browser",
                $this);
        }

        // removing PHP native HTTP headers (if enabled)
        if ($this->removePhpHeaders) {
            foreach (headers_list() as $header) {
                header_remove(explode(":", $header)[0]);
            }
        }

        // sending response HTTP headers
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
    public function sendBody(ResponseInterface $response):void
    {
        if ($this->enableGzCompression) {
            ob_start('ob_gzhandler');
        }
        if (($resource = $response->getBody()->detach()) !== null) {
            fpassthru($resource);
            fclose($resource);
        }
        else {
            echo $response->getBody()->__toString();
        }
    }

}