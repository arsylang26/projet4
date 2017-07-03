<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\BookingTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', CollectionType::class, array(
            'entry_type' => TagType::class))
            ->add('emails', RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses courriels sont différentes.',
                'options' => array('attr' => array('class' => 'email-field')),
                'required' => true,
                'first_options'  => array('label' => 'Votre adresse courriel'),
                'second_options' => array('label' => 'Confirmer votre adresse courriel')))
            ->add('bookingDate', DateType::class)
            ->add('nbTicket', ChoiceType::class)
            ->add('dayLong',ChoiceType::class)
        ;
    }

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults(array(
'data_class' => BookingTicket::class,
));
}
}
