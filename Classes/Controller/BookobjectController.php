<?php
namespace WSR\Booking\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;

/***
 *
 * This file is part of the "Booking" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Joachim Ruhs <postmaster@joachim-ruhs.de>, Web Services Ruhs
 *
 ***/
/**
 * BookobjectController
 */
class BookobjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * bookobjectRepository
     * 
     * @var \WSR\Booking\Domain\Repository\BookobjectRepository
     */
    protected $bookobjectRepository = null;

    /**
     * Inject a BookobjectRepository to enable DI
     *
     * @param \WSR\Booking\Domain\Repository\BookobjectRepository
     * @return void
     */
    public function injectBookobjectRepository(\WSR\Booking\Domain\Repository\BookobjectRepository $bookobjectRepository) {
        $this->bookobjectRepository = $bookobjectRepository;
    }


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
    public function injectBookRepository(\WSR\Booking\Domain\Repository\BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }




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
     * @ignorevalidation $bookobject
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
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->bookobjectRepository->update($bookobject);
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
    }

    /**
     * action calendarBase
     * 
     * @return void
     */
    public function calendarBaseAction()
    {
		$bookObjects = $this->bookobjectRepository->findAll();
		$this->view->assign('id', $GLOBALS['TSFE']->id);
		$this->view->assign('settings', $this->settings);
		$this->view->assign('month', date('m', time()));
		$this->view->assign('year', date('Y', time()));
//		$this->view->assign('bookObjects', $bookObjects);
		return;
    }


	/**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Message\ResponseInterface      $response
	 */
	public function indexAction(ServerRequestInterface $request)
	{
		switch ($request->getMethod()) {
			case 'GET':
				$this->processGetRequest($request, $response);
				break;
			case 'POST':
				$this->processPostRequest($request, $response);
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
		$queryParams = $request->getQueryParams();
	
		$frontend = $GLOBALS['TSFE'];

		/** @var TypoScriptService $typoScriptService */
		$typoScriptService = GeneralUtility::makeInstance('TYPO3\CMS\Core\TypoScript\TypoScriptService');
		$this->configuration = $typoScriptService->convertTypoScriptArrayToPlainArray($frontend->tmpl->setup['plugin.']['tx_booking.']);
		$this->settings = $this->configuration['settings'];
		$this->conf['storagePid'] = $this->configuration['persistence']['storagePid'];
	
		$this->request = $request;
		$requestArguments = $this->request->getParsedBody()['tx_booking_ajax'];

		if ($requestArguments['calendar'] === 'month') {	
			$out = $this->showMonth();
		}
		if ($requestArguments['calendar'] === 'week') {	
			$out = $this->showWeek();
		}
		if ($requestArguments['calendar'] === 'form') {	
			$out = $this->showBookingForm();
		}
	
//print_r ($requestArguments);
//exit;
		echo $out;
		return $response;

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
		$requestArguments = $this->request->getParsedBody()['tx_booking_ajax'];

		$year = intval($requestArguments['year']);
		if ($year < date('Y', time()) - 1) $year = date('Y', time()) - 1; 
		if ($year > date('Y', time()) + 1) $year = date('Y', time()) + 1; 
		$month = intval($requestArguments['month']);
		$theYear = $year;


		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);	
		$bookobjectRepository = $objectManager->get('WSR\\Booking\\Domain\\Repository\\BookobjectRepository');
		$bookRepository = $objectManager->get('WSR\\Booking\\Domain\\Repository\\BookRepository');

		$bookObjects = $bookobjectRepository->findAllNew($this->conf['storagePid']);			


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

		$settings['monthLabels'] = explode(',', \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('monthNamesShort', 'booking'));
		$settings['dayLabels'] = explode(',', \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('dayNamesShortMultiRow', 'booking'));		

		
		// loop over the bookingObjects
		for ($o = 0; $o < count($bookObjects); $o++) {
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
			$counts = $bookRepository->getBookingCounts($this->conf['storagePid'], $bookObjects[$o]['uid'], $dayTime);

			
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
				
				
				if (is_array($arrivals)) {
					for ($i = 0; $i < count($arrivals); $i++) {
						if (mktime(0,0,0,$mon,$day,$theYear) >= $arrivals[$i] &&
							mktime(0,0,0,$mon,$day,$theYear) <= $departures[$i]) {
							$booked = 1;
							$booked = $bookingStates[$i];

							if ( (mktime(0,0,0,$mon,$day,$theYear) <= $arrivals[$i]  && mktime(23, 59, 59, $mon,$day,$theYear) >= $arrivals[$i] )) {
								$start = 1;
							}
							else $start = 0;	
							if ( (mktime(0,0,0,$mon,$day,$theYear) <= $departures[$i] && (mktime(23, 59, 59,$mon,$day,$theYear) >= $departures[$i] ))) {
									$end = 1;
							}
							else $end = 0;	
							// now check for startAndEnd
							if ($end && $i < (count($arrivals) - 1)) {
								if ( (mktime(0,0,0,$mon,$day,$theYear) <= $arrivals[$i + 1] && (mktime(23, 59, 59,$mon,$day,$theYear) >= $arrivals[$i + 1] ))) {
									$startAndEnd = 1;
								} else {
									$startAndEnd = 0;
								}
							}
							
						}
	
					}
				}

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
                        $out .= '<td class="bookedWeekend vacantWeekend' . '" '. $title .'>' . '<div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
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
	                        $out .= '<td class="partialDay holiday day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
							
	                        $out .= '<td class="partialDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }



/*
                    if ( ($booked == 1 && $start && !$end && !$startAndEnd)  ) {
                        $out .= '<td class="bookedWeekend requestedDay' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }
*/
					if ( $booked == 0){
						if ($title) {
							$out .= '<td class="vacantWeekend holiday' . '"' . $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
							$out .= '<td class="vacantWeekend"><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
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
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


					// requested && start
                    if ( ($booked == 1 && $start && !$end && !$startAndEnd) ) {
						if ($title) {
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}

                    }

					// requested && end
                    if ( $booked == 1 && $start == 0 && $end == 1 && !$startAndEnd){
						if ($title) {
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '"' . $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" ><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }

					// partialfDay
                    if ( ($booked == 3 && !$start &&  !$end) ) {
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }


					// partialDay && start
                    if ( ($booked == 3 && $start && !$end && !$startAndEnd) ) {
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}

                    }

					// partialDay && end
                    if ( $booked == 3 && $start == 0 && $end == 1 && !$startAndEnd){
						if ($title) {
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '"' . $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" ><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
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
	                        $out .= '<td class="bookedDay holiday' . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						} else {
	                        $out .= '<td class="bookedDay' . '" '.$title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</span></div></td>';
						}
                    }

                    if ( $booked == 0) {
						if ($title) {
							$out .= '<td class="vacantDay holiday day' . $wd . '"' . $title . '><div><span date="' . $d . '.' . $m . '.' . $year .'">' . $d . '</span></div></td>';
						} else {
							$out .= '<td class="vacantDay day' . $wd . '"' . $title . '><div><span date="' . $d . '.' . $m . '.' . $year .'">' . $d . '</span></div></td>';
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
		echo 'in show week';
    }

    /**
     * action showBookingForm
     * 
     * @return void
     */
    public function showBookingForm()
    {
		$requestArguments = $this->request->getParsedBody()['tx_booking_ajax'];
print_r($requestArguments);
		echo 'in show bookingForm';
		
		
    }

    /**
     * action insertBooking
     * 
     * @return void
     */
    public function insertBookingAction()
    {
    }

    /**
     * action deleteBooking
     * 
     * @return void
     */
    public function deleteBookingAction()
    {
    }
}
