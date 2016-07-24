<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class GroupsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('LeaderName')
            ->add('mobile', TextType::class, ['required' => false])
            ->add('day', ChoiceType::class, [
                'choices' => [
                    'monday' => 'monday',
                    'tuesday' => 'tuesday',
                    'wednesday' => 'wednesday',
                    'thursday'=> 'thursday',
                    'friday' => 'friday',
                    'saturday' => 'saturday',
                    'sunday' => 'sunday'
                ],
                'label' => 'Collection Day',
                'placeholder' => 'Choose collection day'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Groups'
        ));
    }
}
