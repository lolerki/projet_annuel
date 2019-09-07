<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 15/03/2019
 * Time: 16:37
 */


namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'label' => 'Image de présentation',
                'attr' => [
                    'class' => 'form-control-file',
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'évenement',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('dateEvent', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'attr' => [
                    'class' => 'js-datepicker form-control',
                ],
                "data" => new \DateTime()
            ])
            ->add('time', TimeType::class, [
                'label' => 'Heure de début',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('timeEnd', TimeType::class, [
                'label' => 'Heure de fin',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de place',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix d\'entrée',
                'attr' => [
                    'class' => 'form-control',
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