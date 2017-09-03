<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class Holiday extends Constraint
{

    public $message = "{{bookingDate}} : les réservations sont impossibles les jours fériés";
}
