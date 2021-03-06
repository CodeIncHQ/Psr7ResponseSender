<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
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
// Date:     23/02/2018
// Time:     13:45
// Project:  Psr7ResponseSender
//
declare(strict_types = 1);
namespace CodeInc\Psr7ResponseSender;
use Psr\Http\Message\ResponseInterface;


/**
 * Interface ResponseSenderInterface
 *
 * @package CodeInc\Psr7ResponseSender
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ResponseSenderInterface
{
	/**
	 * Sends a reponse to the web browser.
	 *
	 * @param ResponseInterface $response
	 */
	public function send(ResponseInterface $response):void;
}