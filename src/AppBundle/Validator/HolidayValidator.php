<?php

namespace AppBundle\Validator;



use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @property RequestStack requestStack
 */
class HolidayValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        $isOK = $this->isOk($value);
        if (!$isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}", $value->format('d/m/Y'))
                ->addViolation();
        }
    }

    public function isOk(\DateTime $date)
    {
        $year = $date->format('Y');
        $holidays = $this->getHolidays($year);
        if (in_array($date->format('d/m'), $holidays)
        ) {
            return false;
        } else {
            return true;
        }
    }


    public function getHolidays($year)
    {
        function my_easter_date($year) {
            return strtotime(
                '+' . easter_days($year) . ' day',
                mktime(0, 0, 0, 3, 21, $year)
            );
        }
        $holidays = array(
            // Dates fixes
            '01/01', '08/05', '14/07', '15/08', '11/11',
            // Dates variables
            date('d/m',my_easter_date($year)+60*60*24),//lundi de pâques(paques+1)
            date('d/m',my_easter_date($year)+60*60*24*39),// jeudi ascension(paques+39)
            date('d/m',my_easter_date($year)+60*60*24*50)//lundi pentecôte(paques+50)
        );
        return $holidays;
    }
}
