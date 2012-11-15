<?php

namespace Cupon\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('email')
            ->add('password')
            ->add('salt')
            ->add('direccion')
            ->add('permite_email')
            ->add('fecha_alta')
            ->add('fecha_nacimiento')
            ->add('dni')
            ->add('numero_targeta')
            ->add('ciudad')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cupon\UsuarioBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'cupon_backendbundle_usuariotype';
    }
}
