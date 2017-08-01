<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BookingTicket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @Route("/payment", name="pay_with_stripe",schemes={"%secure_channel%"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function checkoutAction(Request $request)
    {

        $booking = $request->getSession()->get("booking");
        $amount = $booking->getOrderAmount();
        $user = $booking->getEmail();
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();
            \Stripe\Charge::create(array(
                "amount" => $amount * 100,
                "currency" => "eur",
                "customer" => $user,
                "description" => "Paiement commande"
            ));
            //$this->get('shopping_cart')->emptyCart();//vider le panier
            $this->addFlash('success', 'Paiement effectué avec succès');
            return $this->redirectToRoute('sendEmail');
        }
        return $this->render('BookingTicket/payment.html.twig', array(
            'name' => $user,
            'amount' => $amount,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
    }
}
