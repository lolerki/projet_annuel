<?php

namespace App\Form;

use App\Entity\Customers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('fullname')
            ->add('status')
            ->add('gender')
            ->add('dateOfBirth')
            ->add('description')
            ->add('address')
            ->add('city')
            ->add('countryCode')
            ->add('numberfix')
            ->add('numbermob')
            ->add('discipline')
            ->add('eventType')
            ->add('artistVerify')
            ->add('created_at')
            ->add('userId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customers::class,
        ]);
    }
}
