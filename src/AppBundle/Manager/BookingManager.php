<?php
/**
 * Created by PhpStorm.
 * User: jafa
 * Date: 08/08/2017
 * Time: 22:56
 */

namespace AppBundle\Manager;


use AppBundle\Entity\BookingTicket;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\BookingNotFoundException;
use AppBundle\Service\SendEmail;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class BookingManager
{
    /** @var Session */
    private $session;
    /** @var \Twig_Environment */
    private $twig;
    /** @var SendEmail */
    private $sendEmail;
    /** @var EntityManager */
    private $em;
    /** @var Router */
    private $router;
    /** @var RequestStack  */
    private $request;



    public function __construct(Session $session, \Twig_Environment $twig, SendEmail $sendEmail, RequestStack $request, EntityManager $em, Router $router)
    {
        $this->session = $session;
        $this->twig = $twig;
        $this->sendEmail = $sendEmail;
        $this->request = $request->getCurrentRequest();
        $this->em = $em;
        $this->router = $router;
    }

    public function initBooking()
    {
        $booking = new BookingTicket();

        $this->session->set('booking', $booking);
        return $booking;

    }



    public function delTickets($index)
    {
        $booking = $this->recoverBooking();
        $booking->removeTicket($booking->getTickets()->get($index));
        $booking->computeAmount(); //on recalcule le prix
        $countTicket = count($booking->getTickets());
        $booking->setNbTicket($countTicket); //on recalcule le nombre de ticket
        return $booking;
    }

    /**
     * @return BookingTicket
     */
    public function recoverBooking()
    {
        $booking = $this->session->get('booking', null);
        if ($booking === null) { //si aucune session

            throw new BookingNotFoundException();//lancement de l'exception qui redirige vers l'accueil
        }
        return $booking;
    }

    public function initTickets(BookingTicket $booking)
    {
        for ($i = 1; $i <= $booking->getNbTicket(); $i++) {
            if (count($booking->getTickets()) < $booking->getNbTicket()) {
                $ticket = new Ticket();
                $booking->addTicket($ticket);
            } elseif (count($booking->getTickets()) > $booking->getNbTicket()) {
                $booking->removeTicket($booking->getTickets()->last());
            }

        }
        return $this;

    }

    /**
     * @param BookingTicket $booking
     * @param Request $request
     * @param SendEmail $sendEmail
     * @return boolean
     *
     */


    public function orderConfirm($stripeSecretKey, BookingTicket $booking)
    {

        $amount = $booking->getOrderAmount();
        $orderID = $booking->getId();
        if ($this->request->isMethod('POST')) {
            $token = $this->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            try {
                \Stripe\Charge::create(array(
                    "amount" => $amount * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Paiement commande n° $orderID"
                ));
                $this->em->persist($booking);
                $this->em->flush();
                $this->session->getFlashBag()->add('success', 'Paiement effectué avec succès');
                $this->sendEmail->sendEmail($booking);
                return true;
            } catch (\Exception $exception) {
                $this->session->getFlashBag()->add('error', 'Paiement impossible ' . $exception->getMessage());

            }

        }
        return false;

    }
}