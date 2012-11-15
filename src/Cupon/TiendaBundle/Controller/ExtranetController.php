<?php
namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\TiendaBundle\Form\Extranet\TiendaType;
use Cupon\OfertaBundle\Form\Extranet\OfertaType;
use Cupon\OfertaBundle\Entity\Oferta;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExtranetController extends Controller
{
    public function loginAction()
    {
        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();

        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );

        return $this->render('TiendaBundle:Extranet:login.html.twig', array(
            'error' => $error
        ));
    }

    public function portadaAction()
    {
        $em = $this->getDoctrine();

        $tienda = $this->get('security.context')->getToken()->getUser();
        $ofertas = $em->getRepository('TiendaBundle:Tienda')
            ->findOfertasRecientes($tienda->getId());

        return $this->render('TiendaBundle:Extranet:portada.html.twig', array(
            'ofertas' => $ofertas
        ));
    }

    public function ofertaVentasAction($id)
    {
        $em = $this->getDoctrine();

        $ventas = $em->getRepository('OfertaBundle:Oferta')
            ->findVentasByOferta($id);

        return $this->render('TiendaBundle:Extranet:ventas.html.twig', array(
            'oferta' => $ventas[0]->getOferta(),
            'ventas' => $ventas
        ));
    }

    public function perfilAction()
    {
        $peticion = $this->getRequest();

        $tienda = $this->get('security.context')->getToken()->getUser();
        $formulario = $this->createForm( new TiendaType(), $tienda );

        if ($peticion->getMethod() == 'POST')
        {
            $passwordOriginal = $formulario->getData()->getPassword();

            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                if( null == $tienda->getPassword() )
                {
                    $tienda->setPassword($passwordOriginal);
                } else {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($tienda);
                    $passwordCodificado = $encoder->encodePassword(
                        $tienda->getPassword(),
                        $tienda->getSalt()
                    );
                    $tienda->setPassword($passwordCodificado);
                }
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($tienda);
                $em->flush();

                $this->get('session')->setFlash('info', 'Los datos de tu perfil se han actualizado correctamente');

                return $this->redirect( $this->generateUrl('extranet_portada') );
            }
        }

        return $this->render('TiendaBundle:Extranet:perfil.html.twig', array(
            'tienda'        => $tienda,
            'formulario'    => $formulario->createView()
        ));
    }

    public function ofertaNuevaAction()
    {
        $peticion = $this->getRequest();

        $oferta = new Oferta();
        $formulario = $this->createForm(new OfertaType(), $oferta);

        if($peticion->getMethod() == 'POST')
        {
            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                $tienda = $this->get('security.context')->getToken()->getUser();

                $oferta->setCompras(0);
                $oferta->setTienda($tienda);
                $oferta->setCiudad($tienda->getCiudad());

                $oferta->subirFoto( $this->container->getPArameter('cupon.directorio.imagenes') );

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($oferta);
                $em->flush();

                return $this->redirect($this->generateUrl('extranet_portada'));
            }
        }

        return $this->render('TiendaBundle:Extranet:formulario.html.twig', array(
            'accion'        => 'crear',
            'formulario'    => $formulario->createView()
        ));
    }

    public function ofertaEditarAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $oferta = $em->getRepository('OfertaBundle:Oferta')->find($id);

        if(!$oferta)
        {
            throw $this->createNotFoundException('La oferta no existe');
        }

        $contexto = $this->get('security.context');
        if(false === $contexto->isGranted('EDIT', $oferta))
        {
            throw new AccesssDeniedException();
        }

        if($oferta->getRevisada())
        {
            $this->get('session')->setFlash('error', 'La oferta no se puede modificar porque
                ya ha sido revisada');

            return $this->redirect($this->generateUrl('extranet_portada'));
        }

        $peticion = $this->getRequest();
        $formulario = $this->createForm(new OfertaType(), $oferta);

        if($peticion->getMethod() == 'POST')
        {
            $fotoOriginal = $formulario->getData()->getFoto();

            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                if(null == $oferta->getFoto())
                {
                    $oferta->setFoto($fotoOriginal);
                } else {
                    $directorioFotos = $this->container->getParameter(
                        'cupon.directorio.imagenes'
                    );

                    $oferta->subirFoto($directorioFotos);

                    unlink($directorioFotos.$fotoOriginal);
                }
                $em->persist($oferta);
                $em->flush();

                return $this->redirect(
                    $this->generateUrl('extranet_portada')
                );
            }
        }

        return $this->render('TiendaBundle:Extranet:formulario.html.twig', array(
            'accion'        => 'editar',
            'oferta'        => $oferta,
            'formulario'    => $formulario->createView()
        ));
    }
}
