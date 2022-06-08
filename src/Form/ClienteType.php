<?php

namespace App\Form;

use App\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nombre', null, [
                'label' => 'Nombre',
                'required' => true,
                'invalid_message' => 'El nombre del cliente es obligatorio.', 
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Nombre del nuevo cliente'
                ),
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'invalid_message' => 'El e-mail del cliente es obligatorio.',
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Correo electrónico del nuevo cliente'
                ),
            ])
            ->add('telefono', null, [
                'label' => 'Teléfono',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'title' => 'Teléfono del nuevo cliente'
                ),
            ])
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn button-color text-white'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
