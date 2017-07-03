<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', array('type' => TextType::class, 'required' => true, 'label' => 'votre nom'))
            ->add('firstName', array('type' => TextType::class, 'required' => true, 'label' => 'votre prénom'))
            ->add('birthDate', array('type' => BirthdayType::class, 'label' => 'votre date de naissance'))
            ->add('country', array('type' => CountryType::class, 'required' => true, 'label' => 'votre pays','placeholder'=>'France'))
            ->add('discount', array('type'=>ChoiceType::class,'required'=>true,'label'=>'Tarif réduit','placeholder'=>'non'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class,
        ));
    }
}