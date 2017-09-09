<?php

namespace AppBundle\Validator;


use AppBundle\Entity\BookingTicket;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @property RequestStack requestStack
 */
class HalfDayValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        $isOK = $this->isOk($value);
        if (!$isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}", $this->context->getRoot()->getData()->getBookingDate()->format('d/m/Y'))
                ->addViolation();
        }
    }

    public function isOk($value)
    {
        $currentDate = new \DateTime();
        $today = $currentDate->format('d/m/Y');
        $bookingDate = $this->context->getRoot()->getData()->getBookingDate();
        if ($value) {// si à la journée
            if (($currentDate->format('H:i') > BookingTicket::HALF_DAY_HOUR)
                && ($bookingDate->format('d/m/Y')) == $today
            ) {
                return false; //si c'est aujourd'hui et qu'il est + de 14h00
            }
        }
        return true; //c'est à la demi-journée et/ou pour un autre jour qu'aujourdhui
    }
}
