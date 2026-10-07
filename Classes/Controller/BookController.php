<?php
namespace WSR\Booking\Controller;


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
 * BookController
 */
class BookController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
    }

}
