<?php
/**
 * Created by PhpStorm.
 * User: jafa
 * Date: 13/08/2017
 * Time: 11:13
 */

namespace AppBundle\Service;

use AppBundle\Entity\BookingTicket;


class SendEmail
{
    const EMAIL_MUSEE = 'reservation@louvre.fr';

    /** @var \Twig_Environment */
    private $twig;

    /** @var \Swift_Mailer */
    private $mailer;


    public function __construct(\Twig_Environment $twig, \Swift_Mailer $swift_Mailer)
    {
        $this->twig = $twig;
        $this->mailer = $swift_Mailer;
    }


    public function sendEmail(BookingTicket $booking)
    {
        $message = (new \Swift_Message("Vos billets pour le musée du Louvre"))
            ->setFrom(self::EMAIL_MUSEE)
            ->setCharset('utf-8')
            ->setTo($booking->getEmail())
            ->setBody($this->twig->render('BookingTicket/email.html.twig', array('booking' => $booking)), 'text/html');
        return $this->mailer->send($message);
    }
}
