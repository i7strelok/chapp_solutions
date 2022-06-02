<?php

namespace App\Form;

use App\Entity\Habitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HabitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('descripcion')
            ->add('capacidad')
            ->add('precio_diario')
            ->add('reservas')
            ->add('etiquetas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habitacion::class,
        ]);
    }
}
