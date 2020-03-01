<?php
namespace WSR\Booking\Domain\Model;


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
 * Book
 */
class Book extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * objectuid
     * 
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $objectuid = 0;

    /**
     * startdate
     * 
     * @var int
     */
    protected $startdate = 0;

    /**
     * enddate
     * 
     * @var int
     */
    protected $enddate = 0;

    /**
     * feuseruid
     * 
     * @var string
     */
    protected $feuseruid = '';

    /**
     * memo
     * 
     * @var string
     */
    protected $memo = '';

    /**
     * Returns the objectuid
     * 
     * @return int $objectuid
     */
    public function getObjectuid()
    {
        return $this->objectuid;
    }

    /**
     * Sets the objectuid
     * 
     * @param int $objectuid
     * @return void
     */
    public function setObjectuid($objectuid)
    {
        $this->objectuid = $objectuid;
    }

    /**
     * Returns the startdate
     * 
     * @return int $startdate
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Sets the startdate
     * 
     * @param int $startdate
     * @return void
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     * Returns the enddate
     * 
     * @return int $enddate
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Sets the enddate
     * 
     * @param int $enddate
     * @return void
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    }

    /**
     * Returns the feuseruid
     * 
     * @return string $feuseruid
     */
    public function getFeuseruid()
    {
        return $this->feuseruid;
    }

    /**
     * Sets the feuseruid
     * 
     * @param string $feuseruid
     * @return void
     */
    public function setFeuseruid($feuseruid)
    {
        $this->feuseruid = $feuseruid;
    }

    /**
     * Returns the memo
     * 
     * @return string $memo
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Sets the memo
     * 
     * @param string $memo
     * @return void
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
    }
}
