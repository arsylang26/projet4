<?php

namespace AppBundle\Validator;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @property RequestStack requestStack
 */
class NoBookingDayValidator extends ConstraintValidator
{

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

        if ($date->format('l') == NoBookingDay::NO_BOOKING_DAY
        ) {
            return false;
        } else {
            return true;
        }
    }
}