<?php

namespace App\Form;

use App\Entity\Reserva;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ReservaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {//'row_attr' => ['class' => 'text-editor', 'id' => '...']
        $builder
            ->add('fecha_inicio', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'required' => true,
                'attr' => ['class' => 'disabled'],
                'invalid_message' => 'La fecha de inicio de la reserva es obligatoria.',
            ])
            ->add('fecha_fin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'required' => true,
                'attr' => ['class' => 'disabled'],
                'invalid_message' => 'La fecha de finalización de la reserva es obligatoria.',
            ])
            ->add('numero_huespedes', null, [
                'label' => 'Nº de huéspedes', 
                'required' => true,
                'invalid_message' => 'El número de huéspedes es obligatorio.',
                'attr' => array(
                    'class' => 'form-control disabled',
                    'title' => 'Número de huéspedes para esta reserva'
                ),
            ])
            ->add('cliente', null, [
                'label' => 'Seleccione el cliente'
            ])
            ->add('habitacion', null, [
                'label' => 'Habitación seleccionada'
            ])
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn button-color text-white'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
        ]);
    }
}
