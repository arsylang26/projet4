<?php
namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


/**
 * @property RequestStack requestStack
 */
class ZeroBookingDaysValidator extends ConstraintValidator
{
    private $em;

    // Les arguments déclarés dans la définition du service arrivent au constructeur
    // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(EntityManagerInterface $em)
    {
        $this->em           = $em;
    }
    public function validate($value, Constraint $constraint)
    {
        // On récupère la date de réservation
        // On vérifie si cette IP a déjà posté une candidature il y a moins de 15 secondes
        $isOK = $this->em->getRepository('AppBundle:BookingTicket')->isBookingDateOk($value);
        if (!$isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{bookingDate}}",$value->format('d/m/Y'))
                ->addViolation();
        }
    }
}