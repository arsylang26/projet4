<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\BookingTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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


class BookingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        for($i=1;$i<=BookingTicket::NB_MAX_TICKET;$i++)
        {
           $listNb[$i] = $i;
        }
        $builder

            ->add('email', RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses courriels sont différentes.',
                'options' => array('attr' => array('class' => 'email-field')),
                'required' => true,
                'first_options'  => array('label' => 'Votre adresse courriel'),
                'second_options' => array('label' => 'Confirmer votre adresse courriel')))
            ->add('bookingDate', DateType::class,array('label'=>'Date de réservation','format'=>'dd MMMM yyyy','widget'=>'single_text',
                'attr' => ['class' => 'js-datepicker'],'html5' => false))//affichage de la date courante par défaut
            ->add('nbTicket', ChoiceType::class,array('choices'=>$listNb,'label'=>'nombre de tickets souhaité'))
            ->add('dayLong',ChoiceType::class,array('choices'=>array('non'=>BookingTicket::TYPE_DAY, 'oui'=>BookingTicket::TYPE_HALF_DAY),
                'label'=>'demi-tarif'));

    }

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults(array(
'data_class' => BookingTicket::class,
));
}
}
