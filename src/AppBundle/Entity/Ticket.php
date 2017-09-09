<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Alpha;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    const PRIX_BILLET_ENFANT = 8;
    const PRIX_BILLET_NORMAL = 16;
    const PRIX_BILLET_SENIOR = 12;
    const PRIX_BILLET_REDUIT = 10;
    const TAUX_DEMI_JOURNEE = 60 / 100;

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
     * @ORM\Column(name="firstName", type="string", length=50)
     * @Assert\Length(min="2", max="50", minMessage="2 lettres minimum", maxMessage="prénom trop long",groups={"step2"})
     * @Alpha(groups={"step2"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50)
     * @Assert\Length(min="2", max="50", minMessage="2 lettres minimum", maxMessage="patronyme trop long",groups={"step2"})
     * @Alpha(groups={"step2"})
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     * @Assert\Date(message="le format de la date n'est pas valide",groups={"step2"})
     * @Assert\LessThan("today",message="Vous devez être né(e) pour commander ! ",groups={"step2"})
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=20)
     */
    private $country;


    /**
     * @var bool
     *
     * @ORM\Column(name="discount", type="boolean", nullable=true)
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */

    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_code", type="string")
     */
    private $ticketCode;

    /**
     * @var BookingTicket
     * @ORM\ManyToOne(targetEntity="BookingTicket",inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * Constructor
     */
    public function __construct()
    {


        $this->ticketCode = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);

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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getTicketCode()
    {
        return $this->ticketCode;
    }

    public function setTicketCode($ticketcode)
    {
        $this->ticketCode = $ticketcode;

        return $this;
    }

    public function computePrice()
    {
        $booking = $this->getBooking();
        $age = $this->getAge();
        $discount = $this->getDiscount();
        $dayLong = $booking->getDayLong();
        if ($age >= 12 && $age < 60) {
            $price = self::PRIX_BILLET_NORMAL;
        } elseif ($age < 4) {
            $price = 0;
        } elseif ($age >= 4 && $age < 12) {
            $price = self::PRIX_BILLET_ENFANT;
        } else {
            $price = self::PRIX_BILLET_SENIOR;
        }
//si le tarif réduit est coché et que la condition d'âge est remplie le tarif réduit est appliqué sinon il n'y a pas lieu de tarif réduit
        if ($discount) {
            if ($age >= 18 && $age < 60) {
                $price = self::PRIX_BILLET_REDUIT;
            } else {
                $this->setDiscount(false);
            }
        }

        if (!$dayLong) {
            $price = $price * self::TAUX_DEMI_JOURNEE;
        }
        $this->setPrice($price);
        return $this->price;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\BookingTicket
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set booking
     *
     * @param \AppBundle\Entity\BookingTicket $booking
     *
     * @return Ticket
     */
    public function setBooking(\AppBundle\Entity\BookingTicket $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    public function getAge()
    {
        $age = $this->getBirthDate()->diff(new \DateTime())->y;
        return $age;

    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get discount
     *
     * @return bool
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set discount
     *
     * @param boolean $discount
     *
     * @return Ticket
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }


    //renvoie demi-tarif pour discount=vrai, plein-tarif sinon

    public function getDiscountLabel()
    {
        return ($this->discount) ? "demi-tarif" : "plein tarif";
    }
}
