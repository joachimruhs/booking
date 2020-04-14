<?php
namespace WSR\Booking\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;



/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Joachim Ruhs <postmaster@joachim-ruhs.de>, Web Services Ruhs
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/



class MultiRowCalendarViewHelper extends AbstractViewHelper {
	/**
	* Arguments Initialization
	*/
	public function initializeArguments() {
		$this->registerArgument('year', 'int', 'The year', TRUE);
		$this->registerArgument('month', 'int', 'The month', TRUE);
		$this->registerArgument('bookObject', 'mixed', 'The bookObject', TRUE);
		$this->registerArgument('settings', 'mixed', 'The settings', TRUE);
	}

    /**
    * Returns the calendar of a month
    * 
    * @param array $arguments 
    * @param \Closure $renderChildrenClosure
    * @param RenderingContextInterface $renderingContext
    * @return string
    */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
		$bookObject =  $arguments['bookObject'];
		$year = (int) $arguments['year'];
		$month = (int) $arguments['month'];
		$settings = $arguments['settings'];
		
		$theYear = $year;


		$configurationManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
		$configuration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$conf['storagePid'] = $configuration['persistence']['storagePid'];

		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);	

		// now fetch the bookings of the seminarUid and this year	
		$bookRepository = $objectManager->get('WSR\\Myseminars\\Domain\\Repository\\BookRepository');
		$bookings = $bookRepository->getBookingsOfYear($year, $seminarUid, $conf['storagePid']);			


		$startOfYear = mktime(0, 0, 0, 1, 1, $year);
		$endOfYear = mktime(0, 0, 0, 12, 31, $year);
	
		for ($i = 0; $i < count($bookings); $i++) {
			
			$arrival = $bookings[$i]['seminarstart'];
			$departure = $bookings[$i]['seminarend'];
			$bookingState = $bookings[$i]['bookingstate'];
			$title = $bookings[$i]['schoolname'];
			
			if ( ($arrival >= $startOfYear && $arrival <= $endOfYear) || ($departure >= $startOfYear && $departure <= $endOfYear)) {
				$arrivals[$i] = $arrival;
				$departures[$i] = $departure;
				$bookingStates[$i] = $bookingState;
				$titles[$i] = $title;				
			}		
			
		}	
	
		if ($arrivals) {
//			sort($arrivals);
//			sort($departures);
		}


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


//print_r($arguments);

			$m = $month;
			$out .= '<table class="monthMultiRow">';
                // adding leading zero
                $mon = ($m < 10) ? '0'. $m : $m ;

                if ( $conf['displayMode'] == 'monthMultiRow' ) {
                    if ($column-1 % $conf['calendarColumns'] == 0)

                        $out.= '<tr>';
                        $out .= '<td class="monthMultiRow"><table class="tableMultiRow">
						<tr><td class="monthNameMultiRow bookObjectName" colspan="7">' . $bookObject->getName() . '</td></tr>
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
                        $out .= '<td class="bookedWeekend vacantWeekend' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
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
	                        $out .= '<td class="holiday vacantWeekend day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="vacantWeekend day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
						}
                    }



/*
                    if ( ($booked == 1 && $start && !$end && !$startAndEnd)  ) {
                        $out .= '<td class="bookedWeekend requestedDay' . '" '. $title .'>' . '<div>'.$d .'</div></td>';
                    }
*/
					if ( $booked == 0){
						if ($title) {
							$out .= '<td class="vacantWeekend holiday' . '"' . $title . '><div>' . $d . '</div></td>';
						} else {
							$out .= '<td class="vacantWeekend"><div>' . $d . '</div></td>';
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
	                        $out .= '<td class="requestedDay holiday bookedDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="requestedDay bookedDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
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
	                        $out .= '<td class="partialDay holiday vacantDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="partialDay vacantDay day' . $wd . '" '. $title . '><div><span date="' . $d . '.' . $m .'.' . $year .'">' . $d . '</div></td>';
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
	                        $out .= '<td class="bookedDay holiday' . '" '.$title . '><div>' . $d . '</div></td>';
						} else {
	                        $out .= '<td class="bookedDay' . '" '.$title . '><div>' . $d . '</div></td>';
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
		

		
		return $out;
	}



	 
}

?>