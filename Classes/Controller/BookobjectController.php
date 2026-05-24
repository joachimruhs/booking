<?php
namespace WSR\Booking\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

use TYPO3\CMS\Core\Http\Response;

use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

use TYPO3\CMS\Core\Context\Context;

use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Mail\MailerInterface;

use WSR\Booking\Domain\Repository\BookobjectRepository;
use WSR\Booking\Domain\Repository\BookRepository;
use WSR\Booking\Domain\Repository\FeuserRepository;


/***
 *
 * This file is part of the "Booking" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 - 2026 Joachim Ruhs <postmaster@joachim-ruhs.de>, Web Services Ruhs
 *
 ***/
/**
 * BookobjectController
 */
class BookobjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    public function __construct(
// private LanguageService $languageService;
// no DependencyInjection in Ajax
    ) {
		$this->viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
		$this->bookobjectRepository = GeneralUtility::makeInstance(BookobjectRepository::class);
		$this->bookRepository = GeneralUtility::makeInstance(BookRepository::class);
		$this->feuserRepository = GeneralUtility::makeInstance(FeuserRepository::class);
		$this->mailer = GeneralUtility::makeInstance(MailerInterface::class);
	}

   /**
     * Inject a ViewFactoryInterface
     *
     * @param \TYPO3\CMS\Core\View\ViewFactoryInterface
     * @return void
     */
    public function injectViewFactoryInterface(\TYPO3\CMS\Core\View\ViewFactoryInterface $viewFactoryInterface) {
//        $this->viewFactory = $viewFactoryInterface;
    }




    /**
     * bookobjectRepository
     * 
     * @var \WSR\Booking\Domain\Repository\BookobjectRepository
     */
//    protected $bookobjectRepository = null;

    /**
     * Inject a BookobjectRepository to enable DI
     *
     * @param \WSR\Booking\Domain\Repository\BookobjectRepository
     * @return void
     */
//    public function injectBookobjectRepository(\WSR\Booking\Domain\Repository\BookobjectRepository $bookobjectRepository): void {
//        $this->bookobjectRepository = $bookobjectRepository;
//    }




	
    /**
     * bookRepository
     * 
     * @var \WSR\Booking\Domain\Repository\BookRepository
     */
    protected $bookRepository = null;

    /**
     * Inject a BookRepository to enable DI
     *
     * @param \WSR\Booking\Domain\Repository\BookRepository
     * @return void
     */
//    public function injectBookRepository(\WSR\Booking\Domain\Repository\BookRepository $bookRepository) {
//        $this->bookRepository = $bookRepository;
//    }

	/**
	 * userRepository
	 * 
     * @var \WSR\Booking\Domain\Repository\FeuserRepository
 	 */
	protected $feuserRepository = NULL;

    /**
     * Inject a userRepository to enable DI
     *
     * @param  $feuserRepository
     * @return void
     */
    public function injectFeuserRepository(\WSR\Booking\Domain\Repository\FeuserRepository $feuserRepository) {
        $this->feuserRepository = $feuserRepository;
    }


	/**
	 * usersRepository
	 * 
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
 	 */
//	protected $feUsersRepository = NULL;

    /**
     * Inject a userRepository to enable DI
     *
     * @param \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $feUsersRepository
     * @return void
     */
//    public function injectFeUsersRepository(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $feUsersRepository) {
//        $this->feUsersRepository = $feUsersRepository;
//    }




    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $bookobjects = $this->bookobjectRepository->findAll();
        $this->view->assign('bookobjects', $bookobjects);
    }

    /**
     * action show
     * 
     * @param \WSR\Booking\Domain\Model\Bookobject $bookobject
     * @return void
     */
    public function showAction(\WSR\Booking\Domain\Model\Bookobject $bookobject)
    {
        $this->view->assign('bookobject', $bookobject);
    }

    /**
     * action edit
     * 
     * @param \WSR\Booking\Domain\Model\Bookobject $bookobject
     * @return void
     */
    public function editAction(\WSR\Booking\Domain\Model\Bookobject $bookobject)
    {
        $this->view->assign('bookobject', $bookobject);
    }

    /**
     * action update
     * 
     * @param \WSR\Booking\Domain\Model\Bookobject $bookobject
     * @return void
     */
    public function updateAction(\WSR\Booking\Domain\Model\Bookobject $bookobject)
    {
        $this->addFlashMessage('This function is disabled!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
//        $this->bookobjectRepository->update($bookobject);
        $this->redirect('list');
    }

    /**
     * action showMonth
     * 
     * @return void
     */
    public function showMonthAction()
    {
		$bookObjects = $this->bookobjectRepository->findAll();
		$this->view->assign('settings', $this->settings);
		$this->view->assign('month', 2);
		$this->view->assign('year', date('Y', time()));
		$this->view->assign('bookObjects', $bookObjects);
        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($this->streamFactory->createStream($this->view->render()));        
    }

    /**
     * action calendarBase
     * 
     * @return void
     */
    public function calendarBaseAction()
    {
//		$this->bookobjectRepository = GeneralUtility::makeInstance(BookobjectRepository::class);
//		$this->bookobjectRepository = GeneralUtility::makeInstance(BookRepository::class);

	$bookObjects = $this->bookobjectRepository->findAll();
//        $id = $GLOBALS['TSFE']->contentPid;

        $pageArguments = $this->request->getAttribute('routing');
        $pageId = $pageArguments->getPageId();

        $this->view->assign('id', (int) $pageId);
		$this->view->assign('settings', $this->settings);
		$this->view->assign('month', date('m', time()));
		$this->view->assign('year', date('Y', time()));
		$this->view->assign('bookingDate', time());
        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($this->streamFactory->createStream($this->view->render()));
    }


	/**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param TYPO3\CMS\Core\Http\Response      $response
	 */
	public function indexAction(ServerRequestInterface $request, Response $response)
	{
		switch ($request->getMethod()) {
			case 'GET':
				$response = $this->processGetRequest($request, $response);
				break;
			case 'POST':
				$response = $this->processPostRequest($request, $response);
				break;
			default:
				$response->withStatus(405, 'Method not allowed');
		}
	
		return $response;
	}

	/**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Message\ResponseInterface      $response
	 */
	protected function processGetRequest(ServerRequestInterface $request, ResponseInterface $response) {
		$response->withHeader('Content-type', ['text/html; charset=UTF-8']);
		$response->getBody()->write($view->render());
	}

	/**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Message\ResponseInterface      $response
	 */
	protected function processPostRequest(ServerRequestInterface $request, $response)
	{

	$this->request1 = $request; 
	
	$queryParams = $request->getQueryParams();
	
        $fullTypoScript = $request->getAttribute('frontend.typoscript')->getSetupArray()['plugin.']['tx_booking.'] ;
	    $this->configuration = $request->getAttribute('frontend.typoscript')->getSetupArray()['plugin.']['tx_booking.'];


		$this->settings = $this->configuration['settings.'];
		$this->conf['storagePid'] = $this->configuration['persistence.']['storagePid'];

		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];

        // fetching correct language for locallang labels
 		$languageAspect = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getAspect('language');
		$sys_language_uid = $languageAspect->getId();

        $siteLanguage = $this->request1->getAttribute('language');
        $this->language = $siteLanguage->getTypo3Language();        
        
		// is startingpoint used ?
		if ($requestArguments['startingpoint']) $this->conf['storagePid'] = intval($requestArguments['startingpoint']);

		if ($requestArguments['calendar'] === 'month') {	
			$out = $this->showMonth();
		}
		if ($requestArguments['calendar'] === 'week') {	
			$out = $this->showWeek();
		}
		if ($requestArguments['calendar'] === 'form') {	
			$out = $this->showBookingForm();
		}
	
		if ($requestArguments['calendar'] === 'deleteBooking') {	
			$out = $this->deleteBooking();
		}

		if ($requestArguments['calendar'] === 'insertBooking') {	
			$out = $this->insertBooking();
		}

		if ($requestArguments['calendar'] === 'insertDayBooking') {	
			$out = $this->insertDayBooking();
		}

		if ($requestArguments['calendar'] === 'deleteDayBooking') {	
			$out = $this->deleteDayBooking();
		}
		
		
		return $out;		

		//    $response->getBody()->write(json_encode($queryParams));
		//    $response->getBody()->write($out);
		
		/** @var Response $response */
		//$response = GeneralUtility::makeInstance(Response::class);
		//$response->getBody()->write($out);
		
		//return $response;
/*		
		$view = $this->getView();
		$hasErrors = false;
		// ... some logic
	
		if ($hasErrors) {
			$response->withHeader('Content-type', ['text/html; charset=UTF-8']);
			$response->getBody()->write($view->render());
		} else {
			$response->withHeader('Content-type', ['application/json; charset=UTF-8']);
			$response->getBody()->write(json_encode(['success' => true]));
		}
*/
	}



	function showMonth() {
 		if (Environment::getPublicPath() != Environment::getProjectPath()) {
            //  we are in composerMode
//			$this->view->assign('composerMode', 1);
//echo 'Composer';
        } else {
//			$this->view->assign('composerMode', 0);
//echo 'nonComposer';
			}

	if (!$this->conf['storagePid']) {
			$error = 'No storagePid or startingpoint given! <br />Please insert a storagePid in constant editor for the plugin or set the startingpoint of the plugin in flexform.';
			echo '<div class="error">' . $error . '</div>';
		}

		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];

		$year = intval($requestArguments['year']);
		if ($year < date('Y', time()) - 1) $year = date('Y', time()) - 1; 
		if ($year > date('Y', time()) + 1) $year = date('Y', time()) + 1; 
		$month = intval($requestArguments['month']);
		$theYear = $year;

		$bookObjects = $this->bookobjectRepository->findAllNew($this->conf['storagePid']);			

        $lengthOfMonth = array (1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        if (!$year)
		$theYear = date('Y', time());

        // leap year calculating....
        if ( date("L", mktime(0,0,0,1,1,$theYear)) == 1) {
            $lengthOfMonth[2] = 29;
        }

		$conf['calendarColumns'] = 2; // not used because of responsive design
		$column = 1;
		$conf['displayMode'] = 'monthMultiRow';
		$conf['showDaysShortcuts'] = 1;
		$conf['startOfWeek'] = 'monday';
		$conf['markWeekends'] = 1; 

		$settings['monthLabels'] = explode(',', $this->translate('monthNamesShort', 'booking'));
		$settings['dayLabels'] = explode(',', $this->translate('dayNamesShortMultiRow'));		

        $locale = $this->getLocale();

        if ($locale == 'de-DE') {
            $settings['monthLabels'] = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
            $settings['dayLabels'] = ['M', 'D', 'M', 'D', 'F', 'S', 'S'];
        }
        if ($locale == 'da-DK') {
            $settings['monthLabels'] = ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'];
            $settings['dayLabels'] = ['M', 'D', 'M', 'D', 'F', 'S', 'S'];
        }
        if ($locale == 'lo-LA') {
            $settings['monthLabels'] = ['ມັງກອນ', 'ກຸມພາ', 'ມີນາ', 'ເມສາ', 'ພຶດສະພາ', 'ມິຖຸນາ', 'ກໍລະກົດ', 'ສິງຫາ', 'ກັນຍາ', 'ຕຸລາ', 'ພະຈິກ', 'ທັນວາ'];
            $settings['dayLabels'] = ['ຈ', 'ອ', 'ພ', 'ພຫ', 'ສຸ', 'ສ', 'ອ'];
        }

        // this did not work anymore in TYPO3 14
//        $message = LocalizationUtility::translate(
//            'monthLabels',
//            'booking', []
//            [$count, $tablename],
//        );


        $weekday = 0;
        $out = '';
		// loop over the bookingObjects
		for ($o = 0; $o < count($bookObjects); $o++) {
			$bookobjectUid = $bookObjects[$o]['uid'];
			$m = $month;
			$out .= '<table class="monthMultiRow">';
                // adding leading zero
                $mon = ($m < 10) ? '0'. $m : $m ;

                if ( $conf['displayMode'] == 'monthMultiRow' ) {
//                    if ($column-1 % $conf['calendarColumns'] == 0)

                        $out.= '<tr>';
                        $out .= '<td class="monthMultiRow"><table class="tableMultiRow">
						<tr><td class="monthNameMultiRow bookObjectName" colspan="7">' . $bookObjects[$o]['name'] . '</td></tr>
						<tr><td><div class="prevMonth"><</div></td><td class="monthNameMultiRow" colspan="5">' . $settings['monthLabels'][$m - 1] . ' '  . $year .'</td><td><div class="nextMonth">></div></td></tr>';
                        if ( $conf['showDaysShortcuts'] == 1 ) {
                            // display the daynames
                            $out .= '<tr>';
                            $out .= ($conf['startOfWeek'] == 'sunday')? '<td class="dayNames">'.('Sun').'</td>':'';
                            $out .=
                            '<td class="dayNames">'. $settings['dayLabels'][0].'</td><td class="dayNames">'.$settings['dayLabels'][1].
                            '</td><td class="dayNames">'.$settings['dayLabels'][2].'</td><td class="dayNames">'.$settings['dayLabels'][3].
                            '</td><td class="dayNames">'.$settings['dayLabels'][4].'</td><td class="dayNames">'.$settings['dayLabels'][5];
                            $out .= ($conf['startOfWeek'] == 'monday')?'</td><td class="dayNames">'.$settings['dayLabels'][6].'</td></tr>':'</td></tr>';
                        }
                }

                // calculating the left spaces to get the layout right
                if ( $conf['displayMode'] != 'monthSingleRow' )

				$weekNumber = (int) strftime('%V', strtotime($theYear."-".$mon."-01"));

				if ($weekNumber < 10) $weekNumber = '0' . $weekNumber;
                $out .= "<tr class='cw" . $weekNumber . "' cw='$weekNumber'>";

                $wd = date('w', strtotime($theYear."-".$m."-"."1"));
                if ($conf['startOfWeek'] == 'monday') {
                    $wd = ($wd == 0)? 7 : $wd;
                    if ($wd != 1 ) {
                        for ( $s = 1; $s <  $wd ; $s++){
                               $out .= '<td class="noDay">&nbsp;</td>';
                            }
                    }
                }
                else { // sunday
                    for ( $s = 0; $s <  $wd ; $s++){
                           $out .= '<td class="noDay">&nbsp;</td>';
                         }
                }

            // day loop
            for ($d=1; $d <= $lengthOfMonth[$m]; $d++){

			// fetch the bookings for the object
			$dayTime = strtotime($theYear."-".$mon."-".$d);			
			$counts = $this->bookRepository->getBookingCounts($this->conf['storagePid'], $bookObjects[$o]['uid'], $dayTime);
                $title = '';
                $wd = date('w', strtotime($theYear."-".$m."-". $d));

				$weekNumber = strftime('%V', strtotime($theYear."-".$mon."-".$d));

                if (date("w", strtotime($theYear."-".$mon."-".$d))== 0 || date("w", strtotime($theYear."-".$mon."-".$d))== 6 ){
                    $weekend = 1;
                }
                else $weekend =0;

                // adding leading zero
                $day = ($d < 10) ? '0'. $d : $d ;

				$booked = 0;
				$end = 0;
				$startAndEnd = 0;
				
				// we have only partial and booked and vacant days
				$booked = 0;
				// partial
				if ($counts && $counts < count(explode(',', $bookObjects[$o]['hours']))) {
//echo ' ' . date('d.m.Y', $dayTime) . ' ' . count(explode(',', $bookObjects[$o]['hours'])) . ' ' . $bookObjects[$o]['name'];
					$start = 0;
					$end = 0;
					$booked = 3;
				}
				// booked
				if ($counts && $counts == count(explode(',', $bookObjects[$o]['hours']))) {
//echo date('d.m.Y', $dayTime) . ' ' . count(explode(',', $bookObjects[$o]['hours'])) . ' ' . $bookObjects[$o]['name'];
					$start = 0;
					$end = 0;
					$booked = 2;
				}
				
                // display the day with correct class
                if ( $weekend == 1){
                    // requested
					if ( ($booked == 1 && !$start && !$end)  ) {
                        $out .= '<td class="vacantWeekend requestedWeekend' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }
                    // requested && start not implemented
                    // requested && end not implemented


					// booked
					if ( ($booked == 2 && !$start && !$end)  ) {
                        $out .= '<td class="bookedWeekend vacantWeekend' . '" '. $title .'>' . '<div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
                    }
					// booked && start
                    if ( ($booked == 2 && $start && !$end && !$startAndEnd)  ) {
                        $out .= '<td class="bookedWeekend' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }
   					// booked && end
					if ( ($booked == 2 && !$start && $end && !$startAndEnd)  ) {
                        $out .= '<td class="bookedWeekend' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }


					// partialDay
                    if ( ($booked == 3 && !$start &&  !$end) ) {
						if ($title) {
	                        $out .= '<td class="partialDay holiday day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
							
	                        $out .= '<td class="partialDay day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }



/*
                    if ( ($booked == 1 && $start && !$end && !$startAndEnd)  ) {
                        $out .= '<td class="bookedWeekend requestedDay' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }
*/
					if ( $booked == 0){
						if ($title) {
							$out .= '<td class="vacantWeekend holiday' . '"' . $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
							$out .= '<td class="vacantWeekend"><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
					}
/*  
                    if ( $booked == 1 && $end == 1 && !$startAndEnd ){
                        $out .= '<td class="bookedDay"><div>' . $d . '</div></td>';
                    }
                    if ( $booked == 1 && $startAndEnd){
                        $out .= '<td class="bookingStartAndEnd"><div>' . $d . '</div></td>';
                    }
*/	
					
                }
                if ( $weekend == 0 ) {
					// requested
                    if ( ($booked == 1 && !$start &&  !$end) ) {
						if ($title) {
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


					// requested && start
                    if ( ($booked == 1 && $start && !$end && !$startAndEnd) ) {
						if ($title) {
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}

                    }

					// requested && end
                    if ( $booked == 1 && $start == 0 && $end == 1 && !$startAndEnd){
						if ($title) {
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '"' . $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" ><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }

					// partialfDay
                    if ( ($booked == 3 && !$start &&  !$end) ) {
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" '. $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


					// partialDay && start
                    if ( ($booked == 3 && $start && !$end && !$startAndEnd) ) {
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}

                    }

					// partialDay && end
                    if ( $booked == 3 && $start == 0 && $end == 1 && !$startAndEnd){
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '"' . $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" ><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


					
					//booked && start
                    if ( ($booked == 2 && $start && !$end && !$startAndEnd) ) {
						if ($title) {
	                        $out .= '<td class="bookedDay holiday' . '" '.$title . '><div>' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="bookedDay ' . '" '.$title . '><div>' . $d . '</div></td>';
						}
                    }

					//booked && end
                    if ( $booked == 2 && $start == 0 && $end == 1 && !$startAndEnd){
						if ($title) {
	                        $out .= '<td class="bookedDay holiday' . '" '.$title . '><div>' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="bookedDay"><div>' . $d . '</div></td>';
						}
                    }

					//booked
                     if ( ($booked == 2 && !$start &&  !$end) ) {
						if ($title) {
	                        $out .= '<td class="bookedDay holiday' . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="bookedDay' . '" '.$title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }

                    if ( $booked == 0) {
						if ($title) {
							$out .= '<td class="vacantDay holiday day' . $wd . '"' . $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m . '.' . $year .'">' . $d . '</span></div></td>';
						} else {
							$out .= '<td class="vacantDay day' . $wd . '"' . $title . '><div><span objectUid="' . $bookobjectUid . '" date="' . $d . '.' . $m . '.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


/*					
                    if ( $booked == 1 && $startAndEnd){
                        $out .= '<td class="bookingStartAndEnd"><div>' . $d . '</div></td>';
                    }
                    if ( $booked == 2 && $startAndEnd){
                        $out .= '<td class="bookingStartAndEnd"><div>' . $d . '</div></td>';
                    }
*/
                }

                if ( ( date('w', strtotime($theYear."-".$m."-".$d)) ) == 0 && $conf['displayMode'] == 'monthMultiRow') {
					$weekNumber = strftime('%V', strtotime($theYear."-".$mon."-" . $d)) + 1;
					if ($weekNumber > 53) $weekNumber = 1;

					if ($weekNumber < 10) $weekNumber = '0' . $weekNumber;
					
					if ($d < $lengthOfMonth[$m]) {
	                    $out .= "</tr><tr class='cw" . $weekNumber . "' cw='$weekNumber'>";
					} else {
	                    $out .= "</tr><tr class='cw" . $weekNumber . "' cw='$weekNumber'>";
					}
					$weekday = 0;
                }
				$weekday++;

            } //($d=1; $d <= $lengthOfMonth[$m]; $d++) day-loop

			for ($wd = 0; $wd < 8 - $weekday; $wd++) {
				$out .= '<td></td>';
			}

			$out .= '</table></table>';
		} // bookingObjects loop
		
		return $out;
		
	}




    /**
     * action showWeek
     * 
     * @return void
     */
    public function showWeek()
    {

        $locale = $this->getLocale();

        if (!$this->conf['storagePid']) {
			$error = 'No storagePid or startingpoint given! <br />Please insert a storagePid in constant editor for the plugin or set the startingpoint of the plugin in flexform.';
			echo '<div class="error">' . $error . '</div>';
		}
		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];

		$year = intval($requestArguments['year'] ?? 0);
		if ($year < date('Y', time()) - 1) $year = date('Y', time()) - 1; 
		if ($year > date('Y', time()) + 1) $year = date('Y', time()) + 1; 
		$month = intval($requestArguments['month'] ?? 1);
		$theYear = $year;

		$requestArguments['week'] = $requestArguments['week'] ?? '';
        $week = intval($requestArguments['week']);
		if (!$week) $week = date('W', time());
		$theWeek = $week;
		
        $bookingsAM = [];
        $bookingsPM = [];

//		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);	
//		$bookobjectRepository = $objectManager->get('WSR\\Booking\\Domain\\Repository\\BookobjectRepository');
//		$bookRepository = $objectManager->get('WSR\\Booking\\Domain\\Repository\\BookRepository');

		$bookobjects = $this->bookobjectRepository->findAllNew($this->conf['storagePid']);			

		if (!isset($this->deletedData['bookingDate'])) { //showWeek called not from bookingForm
			if (!isset($requestArguments['bookingDate'])) {
				$requestArguments['bookingDate'] = time();
			}
			
			$month = intval($requestArguments['month'] ?? 1);
			$year = intval($requestArguments['year'] ?? 1);
	
			$bookingDate = $requestArguments['date'];

			$dayOfWeek = date('N', $requestArguments['date']);

			$requestArguments['date'] = strtotime(date('d-m-Y', $requestArguments['date']));

			$startOfWeek = $requestArguments['date'] - ($dayOfWeek - 1) * 86400;
			$endOfWeek = $startOfWeek + 6 * 86400 + 86399;

			$theWeek = date("W", $startOfWeek);
			$theYear = date("Y", $startOfWeek);


			list($day, $month, $year) = GeneralUtility::intExplode('.', date('d.m.Y', $requestArguments['bookingDate']));
			
			if ($day > 31 || $day < 1) $error = 1;
			if ($month > 12 || $month < 1) $error = 1;
			if ($year > 2030 || $year < 2020) $error = 1;
			if (isset($error)) {
				echo '<div class="typo3-messages error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('dateInputError', 'booking') . '</div>';
				exit;
			}
			
			$bookobject	= $this->bookobjectRepository->findByUid(intval($requestArguments['bookobjectUid'] ?? 0));
			// get bookings of the day
			for ($wd = 0; $wd < 7; $wd++) {

				$dayTime = $startOfWeek + $wd * 86400;
				$theDays[] = date('d.m.Y', $dayTime);
				$dayTimes[] = $dayTime;

			if ($this->settings['useNewWeekTemplate']) {
				$bookings[$wd] = $this->bookRepository->getBookingsOfDate($this->conf['storagePid'], $dayTime, $bookobjectUid ?? 0);
			} else {
				$bookingsAM[$wd] = $this->bookRepository->getBookingsOfDateAM($this->conf['storagePid'], $dayTime);
				$bookingsPM[$wd] = $this->bookRepository->getBookingsOfDatePM($this->conf['storagePid'], $dayTime);
			}

			}

		} else { ///////////////////  we have $data form bookingForm ////////////////////
			// the day
			$day = date('d', $this->deletedData['bookingDate']);
			$month = date('m', $this->deletedData['bookingDate']);
			$year = date('Y', $this->deletedData['bookingDate']);
			$dayTime = mktime(0,0,0,$month,$day,$year);

			$month = intval($requestArguments['month']);
			$year = intval($requestArguments['year']);
	
			$bookingDate = $requestArguments['date'];

			$offset = date('z', time());
			$offset = 0;

			$dayOfWeek = date('N', $dayTime);
			$startOfWeek = $requestArguments['date'] - ($dayOfWeek - 1) * 86400;
			$endOfWeek = $startOfWeek + 6 * 86400 + 86399;

			$dayTime = strtotime($day . '-' . $month . '-' . $year);
			for ($wd = 0; $wd < 7; $wd++) {
				$dayTime = $startOfWeek + $wd * 86400;
				$theDays[] = date('d.m.Y', $dayTime);
				$dayTimes[] = $dayTime;

				$bookings[$wd] = $this->bookRepository->getBookingsOfDate($this->conf['storagePid'], $dayTime, $bookobjectUid);

				$bookingsAM[$wd] = $this->bookRepository->getBookingsOfDateAM($this->conf['storagePid'], $dayTime);
				$bookingsPM[$wd] = $this->bookRepository->getBookingsOfDatePM($this->conf['storagePid'], $dayTime);

			}

		}

        $lengthOfMonth = array (1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        if (!$year)
		$theYear = date('Y', time());

        // leap year calculating....
        if ( date("L", mktime(0,0,0,1,1,$theYear)) == 1) {
            $lengthOfMonth[2] = 29;
        }

		
		

//		echo 'in show week';
		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];
		$month = intval($requestArguments['month'] ?? 1);
		$year = intval($requestArguments['year'] ?? 0);
		
		$out = '<div onclick="getCalendar(' . $month . ',' . $year . ', \'month\', \'\');">Month</div> <br/>';
		$out .= '<div onclick="getCalendar(' . $month . ',' . $year . ', \'week\', \'\');">Week</div> <br/>';
		
		if ($this->settings['useNewWeekTemplate']) {
		    $template = 'NewWeek';
			$view = $this->getView();
			$view->assign('hours', GeneralUtility::intExplode(',', $this->settings['hoursToDisplay']));
		} else {
		    $template = 'Week';
		    $view = $this->getView();
		    $view->assign('hours', [0,1,2,3,4,5,6,7,8,9,10,11]);
		}




		$view->assign('out', $out);
		
		$view->assign('message', $this->deletedData['error'] ?? '');
		$view->assign('feUserUid', $this->request1->getAttribute('frontend.user')->user['uid'] ?? 0);
		$view->assign('year', $theYear);
		$view->assign('week', $theWeek);
		$view->assign('bookobjects', $bookobjects);
		$view->assign('weekdays', [0,1,2,3,4,5,6]);
		
		$view->assign('days', $theDays);
		
		$view->assign('dayTime', $dayTime);
		$view->assign('dayTimes', $dayTimes);
		$view->assign('bookobject', $bookobject);
		$view->assign('bookings', $bookings = $bookings ?? []);
	
		$view->assign('bookingsAM', $bookingsAM);
		$view->assign('bookingsPM', $bookingsPM);

		$settings['dayLabels'] = explode(',', $this->translate('dayNamesShort2'));		
//		$view->assign('dayLabels', $settings['dayLabels']);

		$view->assign('calendarWeekLabel', $this->translate('calendarWeek'));

		$view->assign('now', time());
        if ($locale == 'de-DE') {
            $view->assign('dayLabels', ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']);
            $view->assign('calendarWeekLabel', 'Kalenderwoche');
        }
        if ($locale == 'da-DK') {
            $view->assign('dayLabels', ['Ma', 'Ti', 'On', 'To', 'Fr', 'Lø', 'Sø']);
            $view->assign('calendarWeekLabel', 'Kalenderwoche');
        }
        if ($locale == 'lo-LA') {
            $view->assign('dayLabels', ['ຈັນ', 'ອັງ', 'ພຸດ', 'ພຫ', 'ສຸ', 'ເສົາ', 'ອາ']);
            $view->assign('calendarWeekLabel', 'Kalenderwoche');
        }

		return $view->render($template);
    }

    /**
     * action showBookingForm
     * 
     * @return void
     */
    public function showBookingForm()
    {
		if (!isset($this->deletedData['bookingDate'])) { //showBookingForm called not from deleteBooking
			$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];
	
			$requestArguments['year'] = $requestArguments['year'] ?? '';
            if ($requestArguments['year']) {
                $year = intval($requestArguments['year']);
                if ($year < date('Y', time()) - 1) $year = date('Y', time()) - 1; 
                if ($year > date('Y', time()) + 1) $year = date('Y', time()) + 1; 
                $month = intval($requestArguments['month']);
                $theYear = $year;
            }
			$lengthOfMonth = array (1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
			if (!$year)
			$theYear = date('Y', time());
	
			// leap year calculating....
			if ( date("L", mktime(0,0,0,1,1,$theYear)) == 1) {
				$lengthOfMonth[2] = 29;
			}
	
			$month = intval($requestArguments['month']);
			$year = intval($requestArguments['year']);
	
			$bookingDate = $requestArguments['date'];
			list($day, $month, $year) = GeneralUtility::intExplode('.', $bookingDate);
			
			if ($day > 31 || $day < 1) $error = 1;
			if ($month > 12 || $month < 1) $error = 1;
			if ($year > 2030 || $year < 2020) $error = 1;
			if ($error ?? 0) {
				echo '<div class="typo3-messages error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('dateInputError', 'booking') . '</div>';
				exit;
			}
			
	//		$bookingobjectUid = intval($requestArguments['bookingobjectUid']);
			$bookobject	= $this->bookobjectRepository->findByUid(intval($requestArguments['bookobjectUid']));
			// get bookings of the day
			$dayTime = strtotime($day . '-' . $month . '-' . $year);
			$bookings = $this->bookRepository->getBookingsOfDate($this->conf['storagePid'], $dayTime, $requestArguments['bookobjectUid']);

		} else { ///////////////////  we have $data form deleteBooking ////////////////////

			$bookobject	= $this->bookobjectRepository->findByUid(intval($this->deletedData['bookobjectUid']));
			// the day
			$day = date('d', $this->deletedData['bookingDate']);
			$month = date('m', $this->deletedData['bookingDate']);
			$year = date('Y', $this->deletedData['bookingDate']);
			$dayTime = mktime(0,0,0,$month,$day,$year);
			$bookings = $this->bookRepository->getBookingsOfDate($this->conf['storagePid'], $dayTime, $bookobject->getUid());
		}
		$hours = $bookobject->getHours();			
		$hours = GeneralUtility::intExplode(',', $hours);

		for ($h = 0; $h < count($hours); $h++) {
			if (($dayTime + $hours[$h] * 3600) < time())  
			$disabledHours[] = 1;
			else $disabledHours[] = 0;
		}

        $assign = [
            'out' => $out ?? '',
            'message' => $this->deletedData['error'] ?? '',
            'feUserUid' => $this->request1->getAttribute('frontend.user')->user['uid'] ?? 0,
            'dayTime' => $dayTime,
            'bookobject' => $bookobject,
            'bookings' => $bookings,
            'hours' => $hours,
            'disabledHours' => $disabledHours,
            'now' => time(),
            'bookDayLabel' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('bookDay', 'booking'),
            'deleteDayLabel' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('deleteDay', 'booking'),
            'weekViewLabel' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('weekView', 'booking'),
            'monthViewLabel' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('monthView', 'booking'),
            'timeLabel' => LocalizationUtility::translate('time', 'booking'),
            'bookLabel' => LocalizationUtility::translate('book', 'booking'),
            'deleteLabel' => LocalizationUtility::translate('delete', 'booking')
            ];
        $locale = $this->getLocale();
        if ($locale == 'de-DE') {
            $assign['weekViewLabel'] = 'Wochenansicht';
            $assign['monthViewLabel'] = 'Monatsansicht';
            $assign['timeLabel'] = 'Zeit';
            $assign['bookLabel'] = 'Buchen';
            $assign['deleteLabel'] = 'Löschen';
            $assign['deleteDayLabel'] = 'Tag löschen';
            $assign['bookDayLabel'] = 'Tag buchen';
        }
        if ($locale == 'da-DK') {
            $assign['weekViewLabel'] = 'Uge kalender';
            $assign['monthViewLabel'] = 'Måneds kalender';
            $assign['timeLabel'] = 'Tid';
            $assign['memoLabel'] = 'Notad';
            $assign['bookLabel'] = 'Bestil';
            $assign['deleteLabel'] = 'Slet';
            $assign['deleteDayLabel'] = 'Selt dag';
            $assign['bookDayLabel'] = 'Book dag';
        }
        if ($locale == 'lo-LA') {
            $assign['weekViewLabel'] = 'ປື້ມ';
            $assign['monthViewLabel'] = 'ປະຕິທິນລາຍເດືອນ';
            $assign['timeLabel'] = 'ເວລາ';
            $assign['memoLabel'] = 'ບັນທຶກຊ່ວຍຈຳ';
            $assign['bookLabel'] = 'ປື້ມ';
            $assign['deleteLabel'] = 'ປື້ມ';
            $assign['deleteDayLabel'] = 'ລຶບມື້ນີ້';
            $assign['bookDayLabel'] = 'ຈອງມື້ນີ້';
        }

        $template = 'BookingForm.html';


		$view = $this->getView($template);
		$view->assignMultiple($assign);

		return $view->render('BookingForm');
    }

    /**
     * insert booking
     *  
     * @return void
     */
    public function insertBooking()
    {
	$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];
        $error = '';
//print_r($requestArguments);
		$startdate = intval($requestArguments['dayTime']);
		$hour = intval($requestArguments['hour']);
		$bookobjectUid = intval($requestArguments['bookobjectUid']);
		$memo = $requestArguments['memo'];

	    $feUseUid = $feUserUid = $this->request1->getAttribute('frontend.user')->user['uid'] ?? 0;

		$feUserUid = $feUserUid ?? 0;
		if (!$feUserUid) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('insertBookingRequireFeUser', 'booking') . '</div><script>$(".error").center();</script>';
		}
		
		$this->deletedData = ['error' => $error ?? '', 'bookingDate' => $startdate, 'bookobjectUid' => $bookobjectUid]; 

		$startdate = $startdate + $hour * 3600;
		$enddate = $startdate + 3600;
		if ($startdate < time()) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('insertPastBooking', 'booking') . '</div><script>$(".error").center();</script>';
		}
		
		$booking = $this->bookRepository->getBookingOfDateAndBookobject($this->conf['storagePid'], $bookobjectUid, $startdate);
		if ($booking) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('objectAlreadyBooked', 'booking') . '</div><script>$(".error").center();</script>';
		}

		// if no errors, insert booking now
		if ($error == '') {
			$result = $this->bookRepository->insertBooking($this->conf['storagePid'], $bookobjectUid, $startdate, $enddate, $feUserUid, $memo);
			$feUser = $this->feuserRepository->findByUidOverride($feUserUid);
			$bookobject = $this->bookobjectRepository->findByUid($bookobjectUid);

//			$recipient = [$feUser->getEmailOverride() => $feUser->getFirstNameOverride() . ' ' . $feUser->getLastNameOverride()];
//			$sender = [$this->settings['mailFromAddress'] => $this->settings['mailFromName']];

			$recipient = [$feUser['email'], $feUser['first_name'] . ' ' . $feUser['last_name']];
			$sender = [$this->settings['mailFromAddress'], $this->settings['mailFromName']];

			$templateName = '/Email/CustomerMail';
			$variables = [
					'fromName' =>  $this->settings['mailFromName'] ?? '',
					'fromEmail' =>  $this->settings['mailFromEmail'] ?? '',
					'firstname' =>  $feUser['first_name'],
					'lastname' =>  $feUser['last_name'],
					'bookobject' =>  $bookobject,
					'startdate' => $startdate,
					'memo' => $memo
			];
			if ($this->settings['activateFeUserMail'] && $feUser['email']) {
				$this->sendTemplateEmail($recipient, $sender, $this->settings['mailSubject'], $templateName, $variables);
			}
		}
			
		$this->deletedData = ['error' => $error ?? '', 'bookingDate' => $startdate, 'bookobjectUid' => $bookobjectUid]; 

		return $this->showBookingForm();
	}	

	/**
	* @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
	* @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
	* @param string $subject subject of the email
	* @param string $templateName template name (UpperCamelCase)
	* @param array $variables variables to be passed to the Fluid view
	* @return boolean TRUE on success, otherwise false
	*/
	protected function sendTemplateEmail(array $recipient, array $sender, $subject, $templateName, array $variables = array()) {
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
//		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
//										'booking');

		$viewFactoryData = new ViewFactoryData(
            templateRootPaths: $this->configuration['view.']['templateRootPaths.'],
       	    partialRootPaths: ['EXT:booking/Resources/Private/Partials'],
       	    layoutRootPaths: ['EXT:booking/Resources/Private/Layouts'],
//        	    request: $this->request,
        );
        $emailView = $this->viewFactory->create($viewFactoryData);
		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render($templateName);

        $mail = new MailMessage();
        $mail->from(new Address($sender[0], $sender[1]));
        $mail->to(
            new Address($recipient[0], $recipient[1])
        );
        $mail->subject($subject);
        $mail->html($emailBody);

        if ($this->settings['mailAttachment']) {
            $attachment = $this->settings['mailAttachment'];
            $mail->attachFromPath($attachment);
        }
		return $this->mailer->send($mail);
//        $mail->send();
	}

    /**
     * delete booking
     *  
     * @return void
     */
    public function deleteBooking()
    {
		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];
		$bookUid = $requestArguments['bookUid'];
        $error = '';		
		$bookobjectUid = $this->bookRepository->findByUid($bookUid)->getObjectuid();
		$bookobject = $this->bookobjectRepository->findByUid($bookobjectUid);
		
		$startdate = $this->bookRepository->findByUid($bookUid)->getStartdate();

		if ($startdate < time()) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('deletePastBooking', 'booking') . '</div><script>$(".error").center();</script>';
		}

		$feUserUid = $this->request1->getAttribute('frontend.user')->user['uid']; 

		if ($this->bookRepository->findByUid($bookUid)->getFeuseruid() != $feUserUid) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('notAllowedToDeleteBooking', 'booking') . '</div><script>$(".error").center();</script>';
		}

		if ($error == '')
			$result = $this->bookRepository->deleteBooking($bookUid, $feUserUid);

		$this->deletedData = ['error' => $error ?? '', 'bookingDate' => $startdate, 'bookobjectUid' => $bookobjectUid]; 
		return $this->showBookingForm();
	}


    /**
     * delete all bookings of a day
     *  
     * @return string bookingForm
     */
    public function deleteDayBooking()
    {
		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];

		$startdate = $requestArguments['dayTime'];
		$bookobjectUid = $requestArguments['bookobjectUid'];

		$feUserUid = $this->request1->getAttribute('frontend.user')->user['uid'];
		$bookings = $this->bookRepository->getBookingsOfDateAndFeUser($this->conf['storagePid'], $startdate, $requestArguments['bookobjectUid'], $feUserUid);

		$bookobject	= $this->bookobjectRepository->findByUid(intval($bookobjectUid));
		$hours = $bookobject->getHours();			
		$hours = GeneralUtility::intExplode(',', $hours);

		$error = '';
        if (count($bookings) != count($hours)) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('foreignBookingsFound', 'booking') . '</div><script>$(".error").center();</script>';
		}
		
//		if ($this->bookRepository->findByUid($bookUid)->getFeuseruid() != $feUserUid) {
//			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('notAllowedToDeleteBooking', 'booking') . '</div><script>$(".error").center();</script>';
//		}

		if (!$error) {
			for ($i = 0; $i < count($bookings); $i++) {
				$result = $this->bookRepository->deleteBooking($bookings[$i]['uid'], $feUserUid);
			}
		}
		$this->deletedData = ['error' => $error, 'bookingDate' => $startdate, 'bookobjectUid' => $bookobjectUid]; 
		return $this->showBookingForm();
	}


    /**
     * insert day bookings
     *  
     * @return string bookingForm
     */
    public function insertDayBooking()
    {
		$requestArguments = $this->request1->getParsedBody()['tx_booking_ajax'];

		$startdate = $requestArguments['dayTime'];
		$bookobjectUid = $requestArguments['bookobjectUid'];

        $error = '';
		$bookings = $this->bookRepository->getBookingsOfDate($this->conf['storagePid'], $startdate, $requestArguments['bookobjectUid']);

		$bookobject	= $this->bookobjectRepository->findByUid(intval($bookobjectUid));
		$hours = $bookobject->getHours();			
		$hoursArray = GeneralUtility::intExplode(',', $hours);

		if (count($bookings) > 0) {
			$error .= '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('foreignBookingsFound', 'booking') . '</div><script>$(".error").center();</script>';
		}

        $context = GeneralUtility::makeInstance(Context::class);
        $feUserUid = $context->getPropertyFromAspect('frontend.user', 'id'); // Gibt 0 zurück, falls kein User eingeloggt ist

		if (!$feUserUid) {
			$error = '<div class="error">' . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('insertBookingRequireFeUser', 'booking') . '</div><script>$(".error").center();</script>';
		}

		if (!$error) {
			$feUser = $this->feuserRepository->findByUidOverride($feUserUid);
			for ($i = 0; $i < count($hoursArray); $i++) {
				$startdate = $requestArguments['dayTime'] + $hoursArray[$i] * 3600;  
				$enddate = $startdate + 3600;
				$memo = $requestArguments['memo'][$i];
				$result = $this->bookRepository->insertBooking($this->conf['storagePid'], $bookobjectUid, $startdate, $enddate, $feUserUid, $memo);
			}


			if ($this->settings['activateFeUserMail'] && $feUser['email']) {
				$recipient = [$feUser['email'], $feUser['first_name'] . ' ' . $feUser['last_name']];
				$sender = [$this->settings['mailFromAddress'], $this->settings['mailFromName']];
				$templateName = '/Email/CustomerMail';
				$variables = [
					'fromName' =>  $this->settings['mailFromName'],
					'fromEmail' =>  $this->settings['mailFromAddress'],
					'firstname' =>  $feUser['first_name'],
					'lastname' =>  $feUser['last_name'],
					'bookobject' =>  $bookobject,
					'startdate' => $startdate,
					'hours' => $hours,
					'memo' => $memo
				];
				$this->sendTemplateEmail($recipient, $sender, $this->settings['mailSubject'], $templateName, $variables);
			}


		}
		$this->deletedData = ['error' => $error, 'bookingDate' => $startdate, 'bookobjectUid' => $bookobjectUid]; 
		return $this->showBookingForm();
	}


	/**
	 * Renders the fluid template
	 * @param string $template
	 * @param array $assign
	 * @return string
	 */
/*
	public function renderFluidTemplate($template, Array $assign = array()) {
		$templateRootPath = $this->configuration['view']['templateRootPaths'][1];
		
		$templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($templateRootPath . 'Default/' . $template);
		$view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$view->setTemplatePathAndFilename($templatePath);
		$view->assignMultiple($assign);
        $view->getTemplatePaths()->fillDefaultsByPackageName('booking');

        $view->setRequest($this->request1);

		return $view->render();
	}
*/


	/**
	 * @return \TYPO3\CMS\Fluid\View\StandaloneView
	 */
	protected function getView() {
		$viewFactoryData = new ViewFactoryData(
		templateRootPaths: $this->configuration['view.']['templateRootPaths.'],
		partialRootPaths: ['EXT:booking/Resources/Private/Partials'],
		layoutRootPaths: ['EXT:booking/Resources/Private/Layouts'],
//            	request: $this->request,
		);
		$view = $this->viewFactory->create($viewFactoryData);

	    return $view;
	}

    function getLocale() {
        $language = $this->request1->getAttribute('language');
        if ($language instanceof SiteLanguage) {
            $languageId = $language->getLanguageId(); // z.B. 0, 1, 2
            $locale = $language->getLocale(); // z.B. de_DE
        //    $isoCode = $language->getTwoLetterIsoCode(); // z.B. de, en
        return $locale;
        }
        return '';
    }        

	/**
	 * Returns the translation of $key
	 *
	 * @param string $key
	 * @return string
	 */
	protected function translate($key)
	{
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'booking', []);
//        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(key: $key, extensionName: 'booking');
	}
    
}
