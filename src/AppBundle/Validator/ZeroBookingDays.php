<?php


namespace AppBundle\Validator;
use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class ZeroBookingDays extends Constraint
{


    public $message = "{% bookingTicket.bookingDate %} : Réservation impossible à cette date.";
    public function validatedBy()
    {
        return 'app_zerobookingdays'; // Ici, on fait appel à l'alias du service définit dans son tag
    }
}