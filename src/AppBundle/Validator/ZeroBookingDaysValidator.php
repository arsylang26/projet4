<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


/**
 * @property RequestStack requestStack
 */
class ZeroBookingDaysValidator extends ConstraintValidator
{
    private $em;

    // Les arguments déclarés dans la définition du service arrivent au constructeur
    // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        // On récupère la date de réservation

        $isOK = $this->em->getRepository('AppBundle:BookingTicket')->isBookingDateOk($value);
        if (!$isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}", $value->format('d/m/Y'))
                ->addViolation();
        }
    }
    public function isBookingDateOk(\DateTime $date)
    {
        $currentDate = new \DateTime();
        $booking = new BookingTicket();
        if (in_array($date->format('d/m'), BookingTicket::OFF_DAYS) // test sur jours fériés
            || ($date->format('l')) == BookingTicket::WEEKLY_CLOSING_DAY // test sur jour fermeture hebdo
            || (($date->format('H:i') > BookingTicket::HALF_DAY_HOUR)
                && ($date->format('d/m')) == $currentDate->format('d/m'))
            // test sur heure dépassée pour réservation à la demi-journée le jour même


            || ($this->nbBookingPerDate($date->format('d/m')) > 1000) //test sur le nombre de réservations pour ce jour
        ) {
            return false;
        } else {
            return true;
        }
    }
}