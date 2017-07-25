<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ZeroBookingDays extends Constraint
{


    public $message = "{{bookingDate}} : Réservation impossible à cette date.";

}


