<?php
namespace Cupon\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cupon\OfertaBundle\Entity\Venta;
use Cupon\BackendBundle\Form\VentaType;

class VentaController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ventas = $em->getRepository('OfertaBundle:Venta')->findTodasLasVentas();

        return $this->render('BackendBundle:Venta:index.html.twig', array(
            'ventas' => $ventas
        ));
    }

    public function verAction($idOferta, $idUsuario)
    {
        $em = $this->getDoctrine();
        $venta = $em->getRepository('OfertaBundle:Venta')->findVenta($idOferta, $idUsuario);

        if(!$venta)
        {
            throw $this->createNotFoundException('No existe la venta');
        }

        return $this->render('BackendBundle:Venta:ver.html.twig', array(
            'venta' => $venta,
        ));
    }

    public function crearAction()
    {
        $peticion = $this->getRequest();

        $venta = new Venta();
        $formulario = $this->createForm(new VentaType(), $venta);

        if($peticion->getMethod() == 'POST')
        {
            $formulario->bind($peticion);

            if ($formulario->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($venta);
                $em->flush();

                return $this->redirect($this->generateUrl('backend_venta'));
            }
        }

        return $this->render('BackendBundle:Venta:crear.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }

    public function actualizarAction($idOferta, $idUsuario)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $venta = $em->getRepository('OfertaBundle:Venta')->findVenta($idOferta, $idUsuario);

        if(!$venta)
        {
            throw $this->createNotFoundException('No exista esa venta');
        }

        $formulario = $this->createForm(new VentaType(), $venta);

        $peticion = $this->getRequest();
        if ($peticion->getMethod() == 'POST')
        {
            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                $em->persist($venta);
                $em->flush();

                return $this->redirect($this->generateUrl('backend_venta'));
            }
        }

        return $this->render('BackendBundle:Venta:actualizar.html.twig', array(
            'formulario' => $formulario->createView(),
            'venta'     => $venta
        ));
    }

    public function borrarAction($idOferta, $idUsuario)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $venta = $em->getRepository('OfertaBundle:Venta')->findVenta($idOferta, $idUsuario);

        if(!$venta)
        {
            throw $this->createNotFoundException('No exista esa venta');
        }

        $em->remove($venta);
        $em->flush();

        return $this->redirect($this->generateUrl('backend_venta'));
    }
}
