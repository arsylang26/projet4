<?php

namespace AppBundle\Validator;

use AppBundle\Entity\BookingTicket;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NoBookingDay extends Constraint
{

    public $message = "{{bookingDate}} : Pas de réservation possible le ".BookingTicket::NO_BOOKING_DAY;
}