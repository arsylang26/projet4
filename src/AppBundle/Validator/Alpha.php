<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class Alpha extends Constraint
{

    public $message = "Ce champ ne doit contenir que des lettres";
}