<?php
namespace Cupon\OfertaBundle\Form\Extranet;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormError;

class OfertaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('condiciones')
            ->add('foto', 'file', array('required' => false))
            ->add('precio', 'money')
            ->add('descuento', 'money')
            ->add('umbral');

        if(null == $options['data']->getId())
        {
            $builder->add('acepto', 'checkbox', array('mapped' => false));

            $builder->addValidator(
                new CallbackValidator(
                    function($form) {
                        if ($form['acepto']->getData() == false)
                        {
                            $form->addError(new FormError('Debes aceptar las condiciones legales'));
                        }
                    }
                )
            );
        }
    }

    public function setDefultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cupon\OfertaBundle\Entity\Oferta'
        ));
    }

    public function getName()
    {
        return 'oferta_tienda';
    }
}
