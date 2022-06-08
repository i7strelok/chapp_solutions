<?php

namespace App\Form;

use App\Entity\Habitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HabitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', null, [
                'label' => 'Nº de habitación',
                'required' => true,
                'invalid_message' => 'El número de la habitación es obligatorio.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Número que identifica la habitación'
                ),
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción de la habitación',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Descripción de la habitación'
                ),
            ])
            ->add('capacidad', null, [
                'label' => 'Capacidad de la habitación',
                'required' => true,
                'invalid_message' => 'La capacidad de la habitación es obligatoria.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Cantidad de personas que pueden ocupar la habitación'
                ),
            ])
            ->add('precio_diario', null, [
                'label' => 'Precio diario de la habitación',
                'required' => true,
                'invalid_message' => 'El precio diario de la habitación es obligatorio.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Precio diario en euros de la habitación'
                ),
            ])
            //->add('reservas')
            ->add('etiquetas', null, [
                'label' => 'Seleccione etiquetas'
            ])
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn button-color text-white'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habitacion::class,
        ]);
    }
}
