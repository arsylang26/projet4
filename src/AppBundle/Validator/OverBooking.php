<?php

namespace AppBundle\Validator;

use AppBundle\Entity\BookingTicket;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class OverBooking extends Constraint
{

    public $message = "{{bookingDate}} : Déjà trop de réservations (+ de " .BookingTicket::MAX_BOOKING_IN_A_DAY. ").";
}
