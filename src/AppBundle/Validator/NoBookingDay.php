<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NoBookingDay extends Constraint
{
    const NO_BOOKING_DAY='Sunday';
    public $message = "{{bookingDate}} : Pas de réservation possible le dimanche";
}