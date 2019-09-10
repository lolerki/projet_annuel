<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 15/03/2019
 * Time: 16:37
 */


namespace App\Form\Type;

use App\Entity\Event;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

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
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de présentation'
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'évenement *',
                'attr' => [
                    'placeholder' => 'Atelier découverte - peinture sur céramique'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description *'
            ])
            ->add('dateEvent', DateType::class, [
                'label' => 'Date *',
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'attr' => [
                    'class' => 'js-datepicker',
                ],
                "data" => new \DateTime()
            ])
            ->add('time', TimeType::class, [
                'label' => 'Heure de début *'
            ])
            ->add('timeEnd', TimeType::class, [
                'label' => 'Heure de fin *'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse *',
                'attr' => [
                    'autocomplete' => 'off'
                ],
            ])
            ->add('transport', TextType::class, [
                'label' => 'Comment vous trouver',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Metro ligne 9'
                ],

            ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de place *',
                'attr' => [
                    'placeholder' => '15'
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix d\'entrée *',
                'attr' => [
                    'placeholder' => 'Mettez 0 si l\'événement est gratuit'
                ],
            ])
            ->add('lat', HiddenType::class, [
                'attr' => array(
                    'value' => 0
                )
            ])
            ->add('lng', HiddenType::class, [
                'attr' => array(
                    'value' => 0
                )
            ])
            ->add('tags', TagsInputType::class, [
                'label' => 'label.tags',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}