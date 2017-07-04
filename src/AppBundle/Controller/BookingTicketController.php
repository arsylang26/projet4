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
            $em=$this->getDoctrine()->getManager();
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
        $booking=$request->getSession()->get("booking");
        dump($booking);
        $booking->addTicket(new Ticket());
        $booking->addTicket(new Ticket());
        $form = $this->createForm(BookingPage2Type::class, $booking);
        $form->handleRequest($request);
        //ajouter ici les calculs du prix de chaque billet et le calcul du cumul des prix des billets
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->getFlashBag()->add('notice', 'votre commande est validée');
        }


        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('BookingTicket/page2.html.twig', array(
            'form' => $form->createView(),));
    }


    /**
     * @Route("/recapBooking", name="recapBooking")
     */
    public function recapAction() // résumé de la commande et demande de confirmation
    {
        $em = $this->getDoctrine()->getManager();
        $booking = $em->getRepository('AppBundle:BookingTicket');
        $listTickets = $em->getRepository('AppBundle:Ticket')->findBy(array('booking' => $booking));
        
    return $this->render('BookingTicket/recap.html.twig', array('listTickets'=>$listTickets));

    }

    /**
     * @Route("/payment", name="pay_with_stripe")
     */
    public function paymentAction() // paiement de la commande avec stripe
    {
        $em = $this->getDoctrine()->getManager();
        $booking = $em->getRepository('AppBundle:BookingTicket');
        $em->flush();  
    }

    /**
     * @Route("/confirmOrder", name="sendEmail")
     */   
    public function sendEmailAction() // envoi du courriel de confirmation avec résumé de la commande
    {

    }
}