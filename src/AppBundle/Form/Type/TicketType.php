<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName',TextType::class, array('required' => true, 'label' => 'votre nom','attr'=>array('autofocus'=>'autofocus')))
            ->add('firstName',  TextType::class, array('required' => true, 'label' => 'votre prénom'))
            ->add('birthDate', BirthdayType::class, array('label' => 'votre date de naissance','format' => 'dd-MM-yyyy', 'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker-birthdate'], 'html5' => false))
            ->add('country', CountryType::class, array('required' => true, 'label' => 'votre pays','preferred_choices'=>['FR']))
            ->add('discount', CheckboxType::class,array('required'=>false,'label'=>'Tarif réduit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Ticket::class));
    }
}