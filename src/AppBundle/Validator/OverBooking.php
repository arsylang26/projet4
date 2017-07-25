<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class OverBooking extends Constraint
{
    const MAX_BOOKING_IN_A_DAY=1;
    public $message = "{{bookingDate}} : Déjà trop de réservations (+ de ".self::MAX_BOOKING_IN_A_DAY.").";
}