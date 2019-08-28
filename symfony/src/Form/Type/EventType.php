<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 15/03/2019
 * Time: 16:37
 */


namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the custom form field type used to change user's password.
 *
 */
class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'évenement',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('linkgoogle', TextType::class, [
                'label' => 'Lien google map',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix d\'entré',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }
}