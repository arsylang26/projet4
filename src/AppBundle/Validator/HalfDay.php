<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class HalfDay extends Constraint
{

    public $message = "{{bookingDate}} : Trop tard pour un billet à la journée aujourd'hui passé 14h00";
}
