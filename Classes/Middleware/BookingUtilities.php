<?php

namespace WSR\Booking\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Service\TypoScriptService;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\Response;


class BookingUtilities implements MiddlewareInterface {
		
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

		/** @var NormalizedParams $normalizedParams */
		$normalizedParams = $request->getAttribute('normalizedParams');
		$typo3SiteUrl = $normalizedParams->getSiteUrl(); // Same as GeneralUtility::getIndpEnv('TYPO3_SITE_URL')

		$requestArguments = $request->getParsedBody()['tx_booking_ajax'];

		// Remove any output produced until now
		ob_clean();
		// continue only if action is ajaxPsr of extension myleaflet
		if ($requestArguments['action'] != 'ajaxPsr') return $handler->handle($request);

		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');		
		$ajaxController = $objectManager->get('WSR\Booking\Controller\BookObjectController');
		$ajaxController->indexAction($request);
		// when this exit is missing an infinite loop will result

		exit;


	
//		$response = GeneralUtility::makeInstance(Response::class);
//		$response->getBody()->write($out);
	
		// the following code never reached!
        //$response = $handler->handle($request);
 
		// Set caching header for cache servers (like NGINX) in seconds
//        $response = $response->withHeader('X-Accel-Expires', '60');
//		$response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
 
//		$response->getBody()->write('I\'m content fetched via AJAX.' . json_encode($requestArguments));
//        return $response;

    }


}
