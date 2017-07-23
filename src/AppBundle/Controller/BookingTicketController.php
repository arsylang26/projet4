<?php

namespace AppBundle\Controller;


use AppBundle\Entity\BookingTicket;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\BookingPage2Type;
use AppBundle\Form\Type\TicketType;
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
    public function page1Action(Request $request, SessionInterface $session)// saisie du mail du demandeur, de la date de réservation, du type de billet
    {
        // On crée un objet Booking
        $booking = new BookingTicket();
        $session->set("booking", $booking);
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        //ajouter ici le test sur la date de réservation
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $request->getSession()->getFlashBag()->add('notice', 'votre demande va se poursuivre');
            return $this->redirectToRoute("ticketOrder");

        }


        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('BookingTicket/page1.html.twig', array(
            'form' => $form->createView()));

    }


    /**
     * @Route("/ticketOrder", name="ticketOrder")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page2Action(Request $request) // saisie du nom, prénom, date de naissance, pays, tarif réduit pour chaque billet
    {

        $booking = $request->getSession()->get("booking");
        $ticket = new Ticket();

        for ($i = 1; $i <= $booking->getNbTicket(); $i++) {
            if(count($booking->getTickets())<$booking->getNbTicket())
            {
                $booking->addTicket($ticket);
            }
            elseif (count($booking->getTickets())>$booking->getNbTicket()){
                $booking->removeTicket($booking->getTickets()->last());
            }

        }
        $form = $this->createForm(BookingPage2Type::class, $booking);
        $form->handleRequest($request);
        //ajouter ici les calculs du prix de chaque billet et le calcul du cumul des prix des billets


        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->getFlashBag()->add('notice', 'votre commande est validée');
            $price=$ticket->getPrice();
            return $this->redirectToRoute("recapBooking");
        }


        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('BookingTicket/page2.html.twig', array(
            'form' => $form->createView(),));
    }


    /**
     * @Route("/recapBooking", name="recapBooking")
     */
    public function recapAction(Request $request) // résumé de la commande et demande de confirmation
    {
        $booking = $request->getSession()->get("booking");


        return $this->render('BookingTicket/recap.html.twig', array('booking' => $booking));

    }

    /**
     * @Route("/payment", name="pay_with_stripe")
     */
    public function paymentAction() // paiement de la commande avec stripe
    {
        $em = $this->getDoctrine()->getManager();
        $booking = $em->getRepository('AppBundle:BookingTicket');
        $em->flush();
        return $this->render('payment.html.twig',array('booking'=>$booking));
    }

    /**
     * @Route("/confirmOrder", name="sendEmail")
     */
    public function sendEmailAction($booking,\Swift_Mailer $mailer) // envoi du courriel de confirmation avec résumé de la commande
    {

        $message = (new \Swift_Message('Vos tickets d\'entrée au Musée du Louvre'))
            ->setFrom('reservation@louvre.fr')
            ->setTo($booking->getEmail())
            ->setBody($this->renderView('BookingTicket/email.html.twig',array('booking' => $booking)),'text/html');
        $mailer->send($message);

        return $this->redirectToRoute("sendingOk");

    }
/**
* @Route("/confirmation", name="sendingOk")
*/
    public function sendingOKAction(Request $request)
    {
        $booking = $request->getSession()->get("booking");
        return $this->render('BookingTicket/sendingconfirmation.html.twig', array('booking' => $booking));
    }
}
