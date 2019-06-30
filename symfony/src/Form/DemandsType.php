<?php

namespace App\Form;

use App\Entity\Demands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numref')
            ->add('datedemand')
            ->add('message')
            ->add('address')
            ->add('linkgg')
            ->add('status')
            ->add('datedemanded')
            ->add('created_at')
            ->add('customerid')
            ->add('typeofcustomer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demands::class,
        ]);
    }
}
