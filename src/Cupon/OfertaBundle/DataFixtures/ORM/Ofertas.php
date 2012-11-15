<?php
namespace Cupon\OfertaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Cupon\OfertaBundle\Entity\Oferta;
use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\TiendaBundle\Entity\Tienda;
use Gedmo\Translatable\Entity\Translation;

class Ofertas implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $ciudades = $manager->getRepository('CiudadBundle:Ciudad')->findAll();

        foreach ($ciudades as $ciudad)
        {
            $tiendas = $manager->getRepository('TiendaBundle:Tienda')->findByCiudad(
                $ciudad->getId()
            );

            for ($j=1; $j<=20; $j++)
            {
                $oferta = new Oferta();

                $oferta->setNombre($this->getNombre());
                $oferta->setDescripcion($this->getDescription());
                $oferta->setCondiciones($this->getCondiciones());
                $oferta->setFoto('foto'.rand(1,20).'.jpg');
                $oferta->setPrecio(number_format(rand(100, 10000)/100, 2));
                $oferta->setDescuento($oferta->getPrecio() * (rand(10, 70)/100));

                if (1 == $j) {
                    $fecha = 'today';
                    $oferta->setRevisada(true);
                } elseif ($j < 10) {
                    $fecha = 'now - '.($j-1).' days';
                    $oferta->setRevisada((rand(1, 1000) % 10) < 8);
                } else {
                    $fecha = 'now + '.($j - 10 + 1).' days';
                    $oferta->setRevisada(true);
                }

                $fechaPublicacion = new \DateTime($fecha);
                $fechaPublicacion->setTime(23, 59, 59);

                $fechaExpiracion = clone $fechaPublicacion;
                $fechaExpiracion->add(\DateInterval::createFromDateString('24 hours'));

                $oferta->setFechaPublicacion($fechaPublicacion);
                $oferta->setFechaExpiracion($fechaExpiracion);

                $oferta->setCompras(0);
                $oferta->setUmbral(rand(25, 100));

                $oferta->setCiudad($ciudad);

                $tienda = $tiendas[array_rand($tiendas)];
                $oferta->setTienda($tienda);

                $manager->persist($oferta);
                $manager->flush();

                $id = $oferta->getId();
                $offer = $manager->find('OfertaBundle:Oferta', $id);
                $offer->setNombre('ENGLISH '.$oferta->getNombre());
                $offer->setDescripcion('ENGLISH '.$oferta->getDescripcion());
                $offer->setTranslatableLocale('en');

                $manager->persist($offer);
                $manager->flush();

                $proveedor = $this->container->get('security.acl.provider');

                $idObjeto = ObjectIdentity::fromDomainObject($oferta);
                $idUsuario = UserSecurityIdentity::fromAccount($tienda);

                try {
                    $acl = $proveedor->findAcl($idObjeto, array($idUsuario));
                } catch (\Symfony\Component\Security\Acl\Exception\AclNotFoundException $e)
                {
                    $acl = $proveedor->createAcl($idObjeto);
                }

                $aces = $acl->getObjectAces();
                foreach ($aces as $index => $ace)
                {
                    $acl->deleteObjectAce($index);
                }

                $acl->insertObjectAce($idUsuario, MaskBuilder::MASK_OPERATOR);
                $proveedor->updateAcl($acl);
            }
        }
    }

    private function getNombre()
    {
        $palabras = array_flip(array(
            'Lorem', 'Ipsum', 'Sitamet', 'Et', 'At', 'Sed', 'Aut', 'Vel', 'Ut',
            'Dum', 'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque', 'Blandit',
            'Ligula', 'Eget', 'Drerit', 'Malesuada', 'Enimsit', 'Libero',
            'Penatibus', 'Imperdiet', 'Pendisse', 'Vulputae', 'Natoque',
            'Aliquam', 'Dapibus', 'Lacinia'
        ));

        $numeroPalabras = rand(4, 8);

        return implode(' ', array_rand($palabras, $numeroPalabras));
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

        $numerosFrases = rand(4, 7);

        return implode('\n', array_rand($frases, $numerosFrases));
    }

    private function getCondiciones()
    {
        $frases = array_flip(array(
            'Máximo 1 consumición por persona.',
            'No acumulable a otras ofertas.',
            'No disponible para llevar. Debe consumirse en el propio local.',
            'Válido para cualquier día entre semana.',
            'No válido en festivos ni fines de semana.',
            'Reservado el derecho de admisión.',
            'Oferta válida si se realizan consumiciones adicionales por valor de 50 euros.',
            'Válido solamente para comidas, no para cenas.',
        ));

        $numeroFrases = rand(2, 4);

        return implode(' ', array_rand($frases, $numeroFrases));
    }
}
