<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingTicket
 *
 * @ORM\Table(name="booking_ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingTicketRepository")
 */
class BookingTicket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="currentDate", type="datetime")
     */
    private $currentDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingDate", type="date")
     */
    private $bookingDate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTicket", type="integer")
     */
    private $nbTicket;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return BookingTicket
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set currentDate
     *
     * @param \DateTime $currentDate
     *
     * @return BookingTicket
     */
    public function setCurrentDate($currentDate)
    {
        $this->currentDate = $currentDate;

        return $this;
    }

    /**
     * Get currentDate
     *
     * @return \DateTime
     */
    public function getCurrentDate()
    {
        return $this->currentDate;
    }

    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return BookingTicket
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set nbTicket
     *
     * @param integer $nbTicket
     *
     * @return BookingTicket
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }
}

