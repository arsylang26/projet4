<?php

namespace AppBundle\Validator;


use AppBundle\Entity\BookingTicket;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
* @property RequestStack requestStack
*/
class AlphaValidator extends ConstraintValidator
{


    public function validate($value, Constraint $constraint)
    {
      if (!preg_match('/^[\p{Latin}-\s]+$/u', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}