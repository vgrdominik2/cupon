<?php

namespace Cupon\CiudadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function listaCiudadesAction($ciudad)
    {
        $em = $this->getDoctrine();
        $ciudades = $em->getRepository('CiudadBundle:Ciudad')->findAll();

        return $this->render(
            'CiudadBundle:Default:listaCiudades.html.twig',
            array(
                'ciudadActual'      => $ciudad,
                'ciudades'          => $ciudades
            )
        );
    }

    public function recientesAction($ciudad)
    {
        $em = $this->getDoctrine();

        $ciudad = $em->getRepository('CiudadBundle:Ciudad')
                    ->findOneBySlug($ciudad);

        if(!$ciudad) {
            throw $this->createNotFoundException('No existe la ciudad');
        }
        $cercanas = $em->getRepository('CiudadBundle:Ciudad')
                    ->findCercanas($ciudad->getId());
        $ofertas = $em->getRepository('OfertaBundle:Oferta')
                    ->findRecientes($ciudad->getId());

        $formato = $this->get('request')->getRequestFormat();
        //$formato = 'rss';

        return $this->render('CiudadBundle:Default:recientes.'.$formato.'.twig', array(
            'ciudad'        => $ciudad,
            'cercanas'      => $cercanas,
            'ofertas'       => $ofertas
        ));
    }
}