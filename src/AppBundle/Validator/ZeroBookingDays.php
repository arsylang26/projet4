<?php


namespace AppBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ZeroBookingDays extends Constraint
{


    public $message = "{{bookingDate}} : Réservation impossible à cette date.";
   // public function validatedBy()
    //{
        //return 'zerobookingdays_validator'; // Ici, on fait appel à l'alias du service définit dans son tag
    //}
}