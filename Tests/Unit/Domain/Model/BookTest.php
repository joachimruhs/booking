<?php
namespace WSR\Booking\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 */
class BookTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \WSR\Booking\Domain\Model\Book
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \WSR\Booking\Domain\Model\Book();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getObjectuidReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getObjectuid()
        );
    }

    /**
     * @test
     */
    public function setObjectuidForIntSetsObjectuid()
    {
        $this->subject->setObjectuid(12);

        self::assertAttributeEquals(
            12,
            'objectuid',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStartdateReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getStartdate()
        );
    }

    /**
     * @test
     */
    public function setStartdateForIntSetsStartdate()
    {
        $this->subject->setStartdate(12);

        self::assertAttributeEquals(
            12,
            'startdate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEnddateReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getEnddate()
        );
    }

    /**
     * @test
     */
    public function setEnddateForIntSetsEnddate()
    {
        $this->subject->setEnddate(12);

        self::assertAttributeEquals(
            12,
            'enddate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFeuseruidReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFeuseruid()
        );
    }

    /**
     * @test
     */
    public function setFeuseruidForStringSetsFeuseruid()
    {
        $this->subject->setFeuseruid('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'feuseruid',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getMemoReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getMemo()
        );
    }

    /**
     * @test
     */
    public function setMemoForStringSetsMemo()
    {
        $this->subject->setMemo('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'memo',
            $this->subject
        );
    }
}
