<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\BookingTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class BookingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses courriels sont différentes.',
                'options' => array('attr' => array('class' => 'email-field', 'autofocus' => 'autofocus')),
                'required' => true,
                'first_options' => array('label' => 'Votre adresse courriel'),
                'second_options' => array('label' => 'Confirmer votre adresse courriel')))
            ->add('bookingDate', DateTimeType::class,
                array('label' => 'Date de réservation', 'format' => 'dd-MM-yyyy', 'widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker'], 'html5' => false))//affichage de la date courante par défaut
            ->add('nbTicket', ChoiceType::class, array('choices' => array_combine(range(1, BookingTicket::NB_MAX_TICKET), range(1, BookingTicket::NB_MAX_TICKET)), 'label' => 'nombre de tickets souhaité'))
            ->add('dayLong', ChoiceType::class, array('choices' => array('journée' => BookingTicket::TYPE_DAY, 'demi-journée' => BookingTicket::TYPE_HALF_DAY),
                'label' => 'billet journée/demi-journée'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BookingTicket::class,
            'validation_groups' => array("step1")
        ));
    }
}
