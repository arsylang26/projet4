<?php

namespace AppBundle\Controller;


use AppBundle\Entity\BookingTicket;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\BookingNotFoundException;
use AppBundle\Form\Type\BookingPage2Type;
use AppBundle\Form\Type\TicketType;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\SendEmail;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\BookingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class BookingTicketController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page1Action(Request $request, BookingManager $bookingManager)// saisie du mail du demandeur, de la date de réservation, du type de billet
    {

        $form = $this->createForm(BookingType::class, $bookingManager->initBooking());
        $form->handleRequest($request);

        //ajouter ici le test sur la date de réservation
        if ($form->isSubmitted() && $form->isValid()) {



            $request->getSession()->getFlashBag()->add('notice', 'votre demande va se poursuivre');
            return $this->redirectToRoute("ticketOrder");

        }

        return $this->render('BookingTicket/page1.html.twig', array(
            'form' => $form->createView()));

    }


    /**
     * @Route("/ticketOrder", name="ticketOrder")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page2Action(Request $request, BookingManager $bookingManager) // saisie du nom, prénom, date de naissance, pays, tarif réduit pour chaque billet
    {

        $booking = $bookingManager->recoverBooking();
        $bookingManager->initTickets($booking);

        $form = $this->createForm(BookingPage2Type::class, $booking);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $booking->computeAmount();
            return $this->redirectToRoute("recapBooking");
        }

        return $this->render('BookingTicket/page2.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("/recapBooking", name="recapBooking")
     * @param Request $request
     */
    public function recapAction(BookingManager $bookingManager) // résumé de la commande et paiement
    {
        $booking = $bookingManager->recoverBooking();
        if ($bookingManager->orderConfirm($this->getParameter('stripe_secret_key'), $booking)) {
            return $this->redirectToRoute('sendingOk');
        } else {

            return $this->render('BookingTicket/recap.html.twig', array('booking' => $booking,
                'name' => $booking->getEmail(),
                'amount' => $booking->getOrderAmount(),
                'stripe_public_key' => $this->getParameter('stripe_public_key')
            ));
        }
    }


    /**
     * @Route("/confirmation", name="sendingOk")
     */
    public function sendingOKAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->recoverBooking();
        $request->getSession()->invalidate(1);
        return $this->render('BookingTicket/sendingconfirmation.html.twig', array('booking' => $booking));


    }

    /**
     * @Route("/delTicket/{index}", name="delTicket")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delTicketAction($index, SessionInterface $session, BookingManager $bookingManager)
    {
        $booking = $bookingManager->delTickets($index);
        if (count($booking->getTickets())) {
            return $this->redirectToRoute("recapBooking");//s'il est >0
        } else {
            $booking->setNbTicket(1);// s'il est nul on recréé un ticket vierge
            return $this->redirectToRoute("ticketOrder");
        }
    }
}
