<?php
namespace WSR\Booking\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 */
class BookControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \WSR\Booking\Controller\BookController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\WSR\Booking\Controller\BookController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenBookToBookRepository()
    {
        $book = new \WSR\Booking\Domain\Model\Book();

        $bookRepository = $this->getMockBuilder(\WSR\Booking\Domain\Repository\BookRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $bookRepository->expects(self::once())->method('add')->with($book);
        $this->inject($this->subject, 'bookRepository', $bookRepository);

        $this->subject->createAction($book);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenBookFromBookRepository()
    {
        $book = new \WSR\Booking\Domain\Model\Book();

        $bookRepository = $this->getMockBuilder(\WSR\Booking\Domain\Repository\BookRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $bookRepository->expects(self::once())->method('remove')->with($book);
        $this->inject($this->subject, 'bookRepository', $bookRepository);

        $this->subject->deleteAction($book);
    }
}
