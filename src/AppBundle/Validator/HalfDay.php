<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class HalfDay extends Constraint
{
    public $message = "{{bookingDate}} : Trop tard pour commander un billet journée aujourd'hui: essayez avec un billet demi-journée";
}
