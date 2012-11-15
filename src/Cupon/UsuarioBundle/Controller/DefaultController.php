<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Common\Persistence\ObjectManager;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\UsuarioBundle\Form\Frontend\UsuarioType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller
{
    public function registroAction()
    {
        $peticion = $this->getRequest();

        $usuario = new Usuario();
        $usuario->setPermiteEmail(true);
        $usuario->setFechaNacimiento(new \DateTime('today - 18 years'));

        $formulario = $this->createForm(new UsuarioType(), $usuario);

        if ($peticion->getMethod() == 'POST')
        {
            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                $encoder = $this->get('security.encoder_factory')
                                ->getEncoder($usuario);
                $usuario->setSalt(md5(time()));
                $passwordCodificado = $encoder->encodePassword(
                    $usuario->getPassword(),
                    $usuario->getSalt());
                $usuario->setPassword($passwordCodificado);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($usuario);
                $em->flush();

                $this->get('session')->setFlash('info',
                    '¡Enhorabuena! Te has registrado correctamente en Cupon.');

                $token = new UsernamePasswordToken(
                    $usuario,
                    $usuario->getPassword(),
                    'usaurios',
                    $usuario->getRoles()
                );
                $this->container->get('security.context')->setToken($token);

                $ciudadUsuario = $usuario->getCiudad();
                return $this->redirect($this->generateUrl('portada', array(
                    'ciudad' => $ciudadUsuario->getSlug()
                )));
            }

            return $this->render(
                'UsuarioBundle:Default:registro.html.twig',
                array('formulario' => $formulario->createView())
            );
        }

        return $this->render('UsuarioBundle:Default:registro.html.twig',
            array('formulario' => $formulario->createView()));
    }
    public function defaultAction()
    {
        $usuario = new Usuario();
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);

        $password = $encoder->encodePassword(
            'la-contraseña-en-claro',
            $usuario->getSalt()
        );

        $usuario->setPassword($password);
    }
    public function comprasAction()
    {
        $usuario = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine();
        $compras = $em->getRepository('UsuarioBundle:Usuario')
                    ->findTodasLasCompras($usuario->getId());

        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
            'compras' => $compras
        ));
    }

    public function loginAction()
    {
        $peticion = $this->getRequest();
        $session = $peticion->getSession();

        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $session->get(SecurityContext::AUTHENTICATION_ERROR)
        );

        return $this->render('UsuarioBundle:Default:login.html.twig', array(
            'last_username'     => $session->get(SecurityContext::LAST_USERNAME),
            'error'             => $error
        ));
    }

    public function cajaLoginAction()
    {
        $peticion = $this->getRequest();
        $session = $peticion->getSession();

        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $session->get(SecurityContext::AUTHENTICATION_ERROR)
        );

        return $this->render('UsuarioBundle:Default:cajaLogin.html.twig', array(
            'last_username'         => $session->get(SecurityContext::LAST_USERNAME),
            'error'                 => $error
        ));
    }

    public function perfilAction()
    {
        $usuario = $this->get('security.context')->getToken()->getUser();

        $formulario = $this->createForm(new UsuarioType(), $usuario);

        $peticion = $this->getRequest();

        if($peticion->getMethod() == 'POST')
        {
            $passwordOriginal = $formulario->getData()->getPassword();

            $formulario->bind($peticion);

            if($formulario->isValid())
            {
                if (null == $usuario->getPassword())
                {
                    $usuario->setPassword($passwordOriginal);
                } else {
                    $encoder = $this->get('security.encoder_factory')
                                    ->getEncoder($usuario);
                    $passwordCodificado = $encoder->encodePassword(
                        $usuario->getPassword(),
                        $usuario->getSalt()
                    );
                    $usuario->setPassword($passwordCodificado);
                }
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($usuario);
                $em->flush();

                $this->get('session')->setFlash('info',
                    'Los datos de tu perfil se han actualizado correctamente');
                return $this->redirect($this->generateUrl('usuario_perfil'));
            }
        }

        return $this->render('UsuarioBundle:Default:perfil.html.twig', array(
            'usuario'       => $usuario,
            'formulario'    => $formulario->createView()
        ));
    }
}
