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

    /**
     * action create
     * 
     * @param \WSR\Booking\Domain\Model\Book $newBook
     * @return void
     */
    public function createAction(\WSR\Booking\Domain\Model\Book $newBook)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
//        $this->bookRepository->add($newBook);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param \WSR\Booking\Domain\Model\Book $book
     * @return void
     */
    public function deleteAction(\WSR\Booking\Domain\Model\Book $book)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
//        $this->bookRepository->remove($book);
        $this->redirect('list');
    }
}
