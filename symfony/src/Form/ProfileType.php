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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
              ->add('imageFile', VichImageType::class, [
                  'required' => false,

              ])
            ->add('about', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('stage_name', ChoiceType::class, [
                'choices'  => [
                    'Théâtre' => 'Théâtre',
                    'Cinéma' => 'Cinéma',
                    'Danse' => 'Danse',
                    'Chant' => 'Chant',
                    'Musique' => 'Musique',
                    'Interpretation' => 'Interpretation',
                    'Doublage' => 'Doublage',
                    'Peinture' => 'Peinture',
                    'Dessin' => 'Dessin',
                    'Sculpture' => 'Sculpture',
                    'Gravure' => 'Gravure',
                    'Performances' => 'Performances',
                    'Audio Visuelle' => 'Audio Visuelle',
                    'Artifices' => 'Artifices',
                    'Spectacle' => 'Spectacle',
                    'Mime' => 'Mime',
                    'Autre' => 'Autre',
                ],
                'label' => 'Profession *',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo / Non de scène *',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('paypal', UrlType::class, [
                'label' => 'Lien paypal',
                'required' => false,
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