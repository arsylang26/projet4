<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class OffDays extends Constraint
{

    public $message = "{{bookingDate}} : le musée est fermé ce jour férié";
}
