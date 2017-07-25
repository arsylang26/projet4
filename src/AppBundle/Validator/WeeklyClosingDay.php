<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class WeeklyClosingDay extends Constraint
{
    const WEEKLY_CLOSING_DAY='Tuesday';
    public $message = "{bookingDate}} : jour de fermeture hebdomadaire.";
}