<?php

namespace AppBundle\Controller;


use AppBundle\Entity\BookingTicket;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\BookingNotFoundException;
use AppBundle\Form\Type\BookingPage2Type;
use AppBundle\Form\Type\TicketType;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\SendEmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

        $booking =$bookingManager->recoverBooking();
        for ($i = 1; $i <= $booking->getNbTicket(); $i++) {//dans une methode bookingManager ->initTickets(avec le for)
            if (count($booking->getTickets()) < $booking->getNbTicket()) {
                $ticket = new Ticket();
                $booking->addTicket($ticket);
            } elseif (count($booking->getTickets()) > $booking->getNbTicket()) {
                $booking->removeTicket($booking->getTickets()->last());
            }

        }
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
     */
    public function recapAction(Request $request,BookingManager $bookingManager,SendEmail $sendEmail) // résumé de la commande et demande de confirmation
    {
        $booking =$bookingManager->recoverBooking();
        $amount = $booking->getOrderAmount();
        $user = $booking->getEmail();
        $orderID=$booking->getId();
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            $em = $this->getDoctrine()->getManager();
            try {
                \Stripe\Charge::create(array(
                    "amount" => $amount * 100,
                    "currency" => "eur",
                    "source"=>$token,
                    "description" => "Paiement commande n° $orderID"
                ));
                $em->persist($booking);
                $em->flush();
                $this->addFlash('success', 'Paiement effectué avec succès');
              $sendEmail->sendEmail($booking);
               return $this->redirectToRoute('sendingOk');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Paiement impossible '.$exception->getMessage());
                $this->redirectToRoute('recapBooking');
            }


        }
        return $this->render('BookingTicket/recap.html.twig', array('booking' => $booking,
            'name' => $user,
            'amount' => $amount,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
    }


    /**
     * @Route("/confirmOrder/{id}", name="sendEmail")
     */
    public function sendEmailAction(BookingTicket $booking, \Swift_Mailer $mailer) // envoi du courriel de confirmation avec résumé de la commande
    {

        $message = (new \Swift_Message('Vos tickets d\'entrée au Musée du Louvre'))
            ->setFrom('reservation@louvre.fr')
            ->setTo($booking->getEmail())
            ->setBody($this->renderView('BookingTicket/email.html.twig', array('booking' => $booking)), 'text/html');
        $mailer->send($message);

        return $this->redirectToRoute("sendingOk",array('booking'=>$booking));

    }

    /**
     * @Route("/confirmation", name="sendingOk")
     */
    public function sendingOKAction(Request $request)
    {
        $booking = $request->getSession()->get("booking");
        $request->getSession()->invalidate(1);
        return $this->render('BookingTicket/sendingconfirmation.html.twig', array('booking' => $booking));


    }

    /**
     * @Route("/delTicket/{index}", name="delTicket")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delTicketAction($index, SessionInterface $session)
    {
        $booking = $session->get('booking');
        $booking->removeTicket($booking->getTickets()->get($index));
        $booking->computeAmount(); //on recalcule le prix
        $countTicket = count($booking->getTickets());
        $booking->setNbTicket($countTicket); //on recalcule le nombre de ticket
        if ($countTicket) {
            return $this->redirectToRoute("recapBooking");//s'il est >0
        } else {
            $booking->setNbTicket(1);// s'il est nul on recréé un ticket vierge
            return $this->redirectToRoute("ticketOrder");
        }
    }
}
