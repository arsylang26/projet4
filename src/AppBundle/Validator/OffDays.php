<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class OffDays extends Constraint
{
    const OFF_DAYS=array('1/05','1/11','25/12');
    public $message = "{{bookingDate}} : le musée est fermé ce jour férié";
}