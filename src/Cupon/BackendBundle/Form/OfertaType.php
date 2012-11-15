<?php

namespace Cupon\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfertaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('slug')
            ->add('descripcion')
            ->add('condiciones')
            ->add('foto')
            ->add('precio')
            ->add('descuento')
            ->add('fecha_publicacion', 'datetime')
            ->add('fecha_expiracion', 'datetime')
            ->add('compras')
            ->add('umbral')
            ->add('revisada')
            ->add('ciudad')
            ->add('tienda')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cupon\OfertaBundle\Entity\Oferta'
        ));
    }

    public function getName()
    {
        return 'cupon_backendbundle_ofertatype';
    }
}
