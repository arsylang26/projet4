<?php

namespace AppBundle\Validator;


use AppBundle\Entity\BookingTicket;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @property RequestStack requestStack
 */
class TooLateValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {dump($value);
        $isOK = $this->isOk($value);
        if (!$isOK) {
            //  déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}", $value->format('d/m/Y'))
                ->addViolation();
        }
    }

    public function isOk(\DateTime $date)
    {
        $currentDate = new \DateTime();
        $today = $currentDate->format('d/m/Y');
        if (($date->format('H:i') > BookingTicket::TOO_LATE_HOUR)
            && ($date->format('d/m/Y')) == $today
        ) {
            return false;
        } else {
            return true;
        }
    }
}
