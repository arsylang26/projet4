<?php

namespace AppBundle\Entity;

use AppBundle\Validator\HalfDay;
use AppBundle\Validator\NoBookingDay;
use AppBundle\Validator\OffDays;
use AppBundle\Validator\OverBooking;
use AppBundle\Validator\TooLate;
use AppBundle\Validator\WeeklyClosingDay;

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
    const NB_MAX_TICKET = 15; // plafond de tickets par commande
    const HALF_DAY_HOUR = '14:00';//heure limite pour la réservation à la journée pour le jour même
    const TOO_LATE_HOUR='18:00';// heure limite de réservation pour le jour même
    const OFF_DAYS = array('1/05', '1/11', '25/12');
    const NO_BOOKING_DAY = 'Sunday';
    const MAX_BOOKING_IN_A_DAY = 1000;
    const WEEKLY_CLOSING_DAY = 'Tuesday';
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
     * @ORM\Column(name="bookingDate", type="datetime")
     * @Assert\DateTime(message="le format de la date n'est pas valide")
     * @Assert\GreaterThanOrEqual("today",message="la date doit être ultérieure à aujourd'hui")
     * @TooLate()
     * @OffDays()
     * @OverBooking()
     * @WeeklyClosingDay()
     * @NoBookingDay()
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
     * @Assert\Valid() //permet la validation des asserts sur l'entité Ticket
     */
    private $tickets;

    /**
     * @var bool
     * @HalfDay()
     * @ORM\Column(name="dayLong", type="boolean",options={"BookingTicket"=BookingTicket::TYPE_DAY})
     */
    private $dayLong = self::TYPE_DAY;

    /**
     * @var float
     * @ORM\Column(name="order_amount", type="float")
     *
     */
    private $orderAmount;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->currentDate = new \DateTime();
        $this->bookingDate = new \DateTime();


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
    public function setBookingDate(\DateTime $bookingDate)
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

    public function getDayLongLabel()
    {
        return ($this->dayLong) ? "journée" : "demi-journée";
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
        $ticket->setBooking($this);//determine l'appartenance du ticket à la réservation
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

    /**
     * Get orderAmount
     *
     * @return float
     */
    public function getOrderAmount()
    {

        return $this->orderAmount;
    }

    /**
     * Set orderAmount
     *
     * @param float $orderAmount
     *
     * @return BookingTicket
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;

        return $this;
    }

    public function computeAmount()
    {
        $orderAmount = 0;
        foreach ($this->tickets as $ticket) {
            $orderAmount += $ticket->computePrice();

        }
        $this->setOrderAmount($orderAmount);
        return $this->orderAmount;
    }
}
