<?php
namespace Cupon\OfertaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cupon\OfertaBundle\Entity\Oferta;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\OfertaBundle\Entity\Venta;

class Ventas implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ofertas = $manager->getRepository('OfertaBundle:Oferta')->findAll();
        $usuarios = $manager->getRepository('UsuarioBundle:Usuario')->findAll();

        foreach ($usuarios as $usuario)
        {
            $compras = rand(0, 10);
            $comprado = array();

            for($i=0; $i<$compras; $i++)
            {
                $venta = new Venta();

                $venta->setFecha(new \DateTime('now - '.rand(0, 250).' hours'));

                $oferta = $ofertas[array_rand($ofertas)];
                while (in_array($oferta->getId(), $comprado)
                    || $oferta->getRevisada() == false
                    || $oferta->getFechaPublicacion() > new \DateTime('now')
                )
                {
                    $oferta = $ofertas[array_rand($ofertas)];
                }
                $comprado[] = $oferta->getId();

                $venta->setOferta($oferta);
                $venta->setUsuario($usuario);

                $manager->persist($venta);

                $oferta->setCompras($oferta->getCompras() + 1);
                $manager->persist($oferta);
            }

            unset($comprado);
        }

        $manager->flush();
    }
}