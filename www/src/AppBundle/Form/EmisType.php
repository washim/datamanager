<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class EmisType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', EntityType::class, [
				'class' => 'AppBundle:Customers',
				'choice_label' => function ($customer) {
                    return $customer->getBookno() . ' => ' . $customer->getName();
                },
				'label' => 'Book No',
				'placeholder' => 'Choose Book No',
			])
            ->add('amount', TextType::class)
			->add('dueDt', DateType::class, [
				'widget' => 'single_text',
				'label' => 'Payment Date'
			])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Emis'
        ));
    }
}
