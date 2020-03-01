<?php
namespace WSR\Booking\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 */
class BookobjectTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \WSR\Booking\Domain\Model\Bookobject
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \WSR\Booking\Domain\Model\Bookobject();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getHoursReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getHours()
        );
    }

    /**
     * @test
     */
    public function setHoursForStringSetsHours()
    {
        $this->subject->setHours('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'hours',
            $this->subject
        );
    }
}
