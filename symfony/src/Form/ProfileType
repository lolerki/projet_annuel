<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 15/03/2019
 * Time: 16:51
 */

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Defines the form used to edit an user.
 */
class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            //  ->add('imageFile', VichImageType::class, [
            //  ]);
            ->add('about', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'first name',
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
            'data_class' => Profile::class,
        ]);
    }
}