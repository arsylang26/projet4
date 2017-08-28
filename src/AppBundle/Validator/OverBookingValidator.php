<?php

namespace AppBundle\Validator;


use AppBundle\Entity\BookingTicket;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @property RequestStack requestStack
 */
class OverBookingValidator extends ConstraintValidator
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
        $isOK = $this->isOk($value);
        if (!$isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}", $value->format('d/m/Y'))
                ->addViolation();
        }
    }

    public function isOk(\DateTime $date)
    {
        $nbTicketsold = $this->em->getRepository('AppBundle:BookingTicket')->nbBookingPerDate($date->format('d/m'));
        $nbTicketBooking = $this->context->getRoot()->getData()->getNbTicket();
        if (($nbTicketBooking + $nbTicketsold) > BookingTicket::MAX_BOOKING_IN_A_DAY) {
            return false;
        } else {
            return true;
        }
    }
}
