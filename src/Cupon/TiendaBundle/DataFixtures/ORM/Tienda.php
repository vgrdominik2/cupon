<?php
namespace Cupon\TiendaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\TiendaBundle\Entity\Tienda;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Tiendas implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $ciudades = $manager->getRepository('CiudadBundle:Ciudad')->findAll();

        $i = 1;
        foreach ($ciudades as $ciudad)
        {
            $numeroTiendas = rand(2, 5);
            for ($j=1; $j <= $numeroTiendas; $j++)
            {
                $tienda = new Tienda();

                $tienda->setNombre($this->getNombre());

                $tienda->setLogin('tienda'.$i);
                $tienda->setSalt(md5(time()));

                $encoder = $this->container->get('security.encoder_factory')->getEncoder($tienda);

                $passwordEnClaro = 'tienda'.$i;
                $passwordCodificado = $encoder->encodePassword(
                    $passwordEnClaro,
                    $tienda->getSalt()
                );
                $tienda->setPassword($passwordCodificado);

                $tienda->setDescripcion($this->getDescription());
                $tienda->setDireccion($this->getDireccion($ciudad));
                $tienda->setCiudad($ciudad);

                $manager->persist($tienda);

                $i++;
            }
        }

        $manager->flush();
    }

    private function getNombre()
    {
        $prefijos = array('Restaurante', 'Cafetería', 'Bar', 'Pub', 'Pizza', 'Burger');
        $nombres = array(
            'Lorem ipsum', 'Sit amet', 'Consecteur', 'Adipiscing elit',
            'Nec sapien', 'Tincidunt', 'Facílisis', 'Nulla scelerisque',
            'Blandit liguia', 'Eget', 'Hendrerit', 'Malesuada', 'Enim sit'
        );

        return $prefijos[array_rand($prefijos)].' '.$nombres[array_rand($nombres)];
    }

    private function getDescription()
    {
        $frases = array_flip(array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Mauris ultricies nunc nec sapien tincidunt facilisis.',
            'Nulla scelerisque blandit ligula eget hendrerit.',
            'Sed malesuada, enim sit amet ultricies semper, elit leo lacinia massa, in tempus nisl ipsum quis libero.',
            'Aliquam molestie neque non augue molestie bibendum.',
            'Pellentesque ultricies erat ac lorem pharetra vulputate.',
            'Donec dapibus blandit odio, in auctor turpis commodo ut.',
            'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'Nam rhoncus lorem sed libero hendrerit accumsan.',
            'Maecenas non erat eu justo rutrum condimentum.',
            'Suspendisse leo tortor, tempus in lacinia sit amet, varius eu urna.',
            'Phasellus eu leo tellus, et accumsan libero.',
            'Pellentesque fringilla ipsum nec justo tempus elementum.',
            'Aliquam dapibus metus aliquam ante lacinia blandit.',
            'Donec ornare lacus vitae dolor imperdiet vitae ultricies nibh congue.',
        ));

        $numerosFrases = rand(3, 6);

        return implode(' ', array_rand($frases, $numerosFrases));
    }

    private function getDireccion(Ciudad $ciudad)
    {
        $prefijos = array('Calle', 'Avenida', 'Plaza');
        $nombres = array(
            'Lorem', 'Ipsum', 'Sitamet', 'Consectetur', 'Adipiscing',
            'Necsapien', 'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque',
            'Blandit', 'Ligula', 'Eget', 'Hendrerit', 'Malesuada', 'Enimsit'
        );

        return $prefijos[array_rand($prefijos)].' '.$nombres[array_rand($nombres)].', '.rand(1, 100)."\n"
            .$this->getCodigoPostal().' '.$ciudad->getNombre();
    }

    private function getCodigoPostal()
    {
        return sprintf('%02s%03s', rand(1,52), rand(0, 999));
    }
}