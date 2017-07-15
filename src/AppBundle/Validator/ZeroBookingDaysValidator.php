<?php
namespace OC\PlatformBundle\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


class ZeroBookingDaysValidator extends ConstraintValidator
{



    // Les arguments déclarés dans la définition du service arrivent au constructeur
    // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em           = $em;
    }
    public function validate($value, Constraint $constraint)
    {
        // Pour récupérer l'objet Request tel qu'on le connait, il faut utiliser getCurrentRequest du service request_stack
        $request = $this->requestStack->getCurrentRequest();
        // On récupère la date de réservation
        $date = $request->getBookinDate();
        // On vérifie si cette IP a déjà posté une candidature il y a moins de 15 secondes
        $isOK = $this->em
            ->getRepository('AppBundle:BookingTicket')
            ->isFlood($ip, 15)
        ;
        if ($isOK) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->addViolation($constraint->message);
        }
    }
}