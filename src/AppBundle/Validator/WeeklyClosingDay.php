<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class WeeklyClosingDay extends Constraint
{

    public $message = "{{bookingDate}} : jour de fermeture hebdomadaire.";
}