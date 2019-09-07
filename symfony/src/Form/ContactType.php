<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 15/03/2019
 * Time: 16:51
 */

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to edit an user.
 */
class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('object', TextType::class, [
                'label' => 'Object',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}