<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="t_ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TTicketRepository")
 */
class Ticket
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
     * @ORM\Column(name="firstName", type="string", length=50)
     * @Assert\Length(min="2", max="50", minMessage="2 lettres minimum", maxMessage="prénom trop long")
     * @Assert\Type(type="string", message="format du prénom bizzaroïde: {{ value }} n'est pas valide.")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50)
     * @Assert\Length(min="2", max="50", minMessage="2 lettres minimum", maxMessage="patronyme trop long")
     * @Assert\Type(type="integer", message="format du nom bizzaroïde: {{ value }} n'est pass valide.")
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
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
     * @var bool
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */

    private $price;


    /**
     * @var BookingTicket
     * @ORM\ManyToOne(targetEntity="BookingTicket",inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;






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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
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
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
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
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
}
