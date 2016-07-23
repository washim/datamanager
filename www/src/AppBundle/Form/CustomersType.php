<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', EntityType::class, [
				'class' => 'AppBundle:Groups',
				'choice_label' => 'name',
				'label' => 'Group Name',
				'placeholder' => 'Choose Group Name',
			])
			->add('path', FileType::class, ['label' => 'Customer Picture','required' => false,'data_class' => null])
			->add('bookno', TextType::class)
			->add('name', TextType::class)
            ->add('address')
            ->add('gurdian', TextType::class)
            ->add('mobile', TextType::class, ['required' => false])
			->add('ProductNo', TextType::class)
            ->add('ProductName', TextType::class)
            ->add('ProductPrice', TextType::class)
            ->add('AdvancePaid', TextType::class)
            ->add('AdvancePaidDate', DateType::class, ['widget' => 'single_text'])
            ->add('totalEmiNo', TextType::class)
            ->add('emiAmount', TextType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customers'
        ));
    }
}
