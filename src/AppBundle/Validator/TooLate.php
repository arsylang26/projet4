<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class TooLate extends Constraint
{

    public $message = "{{bookingDate}} : Trop tard pour commander un billet aujourd'hui";
}
