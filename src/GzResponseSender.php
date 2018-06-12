<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
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
// Date:     12/06/2018
// Time:     12:33
// Project:  Psr7ResponseSender
//
declare(strict_types=1);
namespace CodeInc\Psr7ResponseSender;
use Psr\Http\Message\ResponseInterface;


/**
 * Class GzResponseSender
 *
 * @package CodeInc\Psr7ResponseSender
 * @author  Joan Fabrégat <joan@codeinc.fr>
 */
class GzResponseSender extends ResponseSender
{
    /**
     * @var array
     */
    private $gzCompressibleContentTypes = [
        'text/html',
        'text/plain',
        'text/css',
        'application/javascript',
        'application/json'
    ];

    /**
     * Returns the list of media types on which the GZ compression is enabled (if enabled).
     *
     * @return array
     */
    public function getGzCompressibleContentTypes():array
    {
        return $this->gzCompressibleContentTypes;
    }

    /**
     * Sets the list of media types on which the GZ compression is enabled (if enabled).
     *
     * @param iterable $contentTypes
     */
    public function setGzCompressibleContentTypes(iterable $contentTypes):void
    {
        if ($contentTypes instanceof \Traversable) {
            $this->gzCompressibleContentTypes = iterator_to_array($contentTypes);
        }
        else {
            $this->gzCompressibleContentTypes = (array)$contentTypes;
        }
    }

    /**
     * @inheritdoc
     * @param ResponseInterface $response
     * @throws ResponsSenderException
     */
    public function send(ResponseInterface $response):void
    {
        if (in_array($this->getResponseContentType($response), $this->gzCompressibleContentTypes)) {
            $this->sendGzResponseHttpHeaders($response);
            $this->sendGzResponseBody($response);
        }
        else {
            $this->sendResponseHttpHeaders($response);
            $this->sendResponseBody($response);
        }
    }

    /**
     * @inheritdoc
     * @param ResponseInterface $response
     * @throws ResponsSenderException
     */
    public function sendGzResponseHttpHeaders(ResponseInterface $response):void
    {
        parent::sendResponseHttpHeaders($response->withoutHeader('Content-Length'));
    }

    /**
     * @inheritdoc
     * @param ResponseInterface $response
     */
    public function sendGzResponseBody(ResponseInterface $response):void
    {
        if (in_array($this->getResponseContentType($response), $this->gzCompressibleContentTypes)) {
            ob_start('ob_gzhandler');
        }
        parent::sendResponseBody($response);
    }

    /**
     * Returns the response's content type. If multiple content types are present in the response headers,
     * the method retruns the last one.
     *
     * It returns null if no content type is found in the response heaers.
     *
     * @param ResponseInterface $response
     * @return null|string
     */
    private function getResponseContentType(ResponseInterface $response):?string
    {
        $contentType = null;
        foreach ($this->listResponseHeaders($response) as $header => $value) {
            if ($header == 'Content-Type') {
                $contentType = explode(';', $value)[0];
            }
        }
        return $contentType;
    }
}