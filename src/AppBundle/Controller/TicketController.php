<?php

namespace AppBundle\Controller;


use AppBundle\Entity\BookingTicket;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TicketController extends Controller
{

    /**
     * @Route("/delTicket", name="delTicket")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delTicket($index)
    {
        $booking=new BookingTicket();
      $booking->removeTicket($index);
      return  $this->redirectToRoute("recapBooking");
    }


    /**
     * @Route("/delTicket", name="modTicket")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modTicket($index)
    {
//appel du ticketrepository updateTicket
        return $this->redirectToRoute("ticketOrder");
    }
}