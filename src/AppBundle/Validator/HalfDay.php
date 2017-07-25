<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class HalfDay extends Constraint
{
    const HALF_DAY_HOUR='14:00';
    public $message = "{{bookingDate}} : Trop tard pour un billet demi-tarif aujourd'hui";
}