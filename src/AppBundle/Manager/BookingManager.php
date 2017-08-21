<?php
/**
 * Created by PhpStorm.
 * User: jafa
 * Date: 08/08/2017
 * Time: 22:56
 */

namespace AppBundle\Manager;


use AppBundle\Entity\BookingTicket;
use AppBundle\Exception\BookingNotFoundException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingManager
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function initBooking()
    {
        $booking = new BookingTicket();

        $this->session->set('booking', $booking);
        return $booking;

    }

    /**
     * @return BookingTicket
     */
    public function recoverBooking()
    {
        $booking = $this->session->get('booking', null);
        if ($booking === null) { //si aucune session

            throw new BookingNotFoundException();//creer class propre exception extend exception si exception levée->homepage avec listener kernel exception if instanceof
        }
        return $booking;
    }
}