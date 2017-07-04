<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * BookingTicket
 *
 * @ORM\Table(name="booking_ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingTicketRepository")
 */
class BookingTicket
{


    const TYPE_DAY = true;
    const TYPE_HALF_DAY = false;

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
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\NotBlank(message="entrer une adresse courrier")
     * @Assert\Email(checkMX=true,message="l'adresse courriel '{{ value }}' n'est pas valide: vérifiez votre saisie.")
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(name="currentDate", type="datetime")
     */
    private $currentDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="bookingDate", type="date")
     */
    private $bookingDate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTicket", type="integer")
     *
     */
    private $nbTicket;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Ticket",mappedBy="booking",cascade={"persist"})
     *
     */
    private $tickets;

    /**
     * @var bool
     *
     * @ORM\Column(name="dayLong", type="boolean",options={"BookingTicket"=BookingTicket::TYPE_DAY})
     */
    private $dayLong = self::TYPE_DAY;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->currentDate = new \DateTime();

    }

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
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Get currentDate
     *
     * @return \DateTime
     */
    public function getCurrentDate()
    {
        return $this->currentDate;
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
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
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
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
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
     * Get dayLong
     *
     * @return boolean
     */
    public function getDayLong()
    {
        return $this->dayLong;
    }

    /**
     * Set dayLong
     *
     * @param boolean $dayLong
     *
     * @return BookingTicket
     */
    public function setDayLong($dayLong)
    {
        $this->dayLong = $dayLong;

        return $this;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return BookingTicket
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
