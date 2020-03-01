<?php
namespace WSR\Booking\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 */
class BookobjectControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \WSR\Booking\Controller\BookobjectController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\WSR\Booking\Controller\BookobjectController::class)
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
    public function listActionFetchesAllBookobjectsFromRepositoryAndAssignsThemToView()
    {

        $allBookobjects = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bookobjectRepository = $this->getMockBuilder(\WSR\Booking\Domain\Repository\BookobjectRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $bookobjectRepository->expects(self::once())->method('findAll')->will(self::returnValue($allBookobjects));
        $this->inject($this->subject, 'bookobjectRepository', $bookobjectRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('bookobjects', $allBookobjects);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBookobjectToView()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('bookobject', $bookobject);

        $this->subject->showAction($bookobject);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenBookobjectToView()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('bookobject', $bookobject);

        $this->subject->editAction($bookobject);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenBookobjectInBookobjectRepository()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $bookobjectRepository = $this->getMockBuilder(\WSR\Booking\Domain\Repository\BookobjectRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $bookobjectRepository->expects(self::once())->method('update')->with($bookobject);
        $this->inject($this->subject, 'bookobjectRepository', $bookobjectRepository);

        $this->subject->updateAction($bookobject);
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBookobjectToView()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('bookobject', $bookobject);

        $this->subject->showAction($bookobject);
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBookobjectToView()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('bookobject', $bookobject);

        $this->subject->showAction($bookobject);
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBookobjectToView()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('bookobject', $bookobject);

        $this->subject->showAction($bookobject);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenBookobjectFromBookobjectRepository()
    {
        $bookobject = new \WSR\Booking\Domain\Model\Bookobject();

        $bookobjectRepository = $this->getMockBuilder(\WSR\Booking\Domain\Repository\BookobjectRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $bookobjectRepository->expects(self::once())->method('remove')->with($bookobject);
        $this->inject($this->subject, 'bookobjectRepository', $bookobjectRepository);

        $this->subject->deleteAction($bookobject);
    }
}
