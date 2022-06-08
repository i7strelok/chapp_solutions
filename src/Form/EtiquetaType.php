<?php

namespace App\Form;

use App\Entity\Etiqueta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class EtiquetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', null, [
                'label' => 'Nombre',
                'required' => true,
                'invalid_message' => 'El nombre de la etiqueta es obligatorio.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Nombre de la nueva etiqueta'
                ),
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'invalid_message' => 'La descripción de la etiqueta es obligatoria.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Descripción de la etiqueta'
                ),
            ])
            ->add('habitaciones', null, [
                'label' => 'Seleccione las habitaciones',
                'required' => true,
            ])
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn button-color text-white'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etiqueta::class,
        ]);
    }
}
