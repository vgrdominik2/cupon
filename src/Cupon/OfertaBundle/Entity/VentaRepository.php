<?php
namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VentaRepository extends EntityRepository
{
    public  function queryTodasLasVentas()
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    v, o, u FROM OfertaBundle:Venta v
            JOIN      v.oferta o JOIN v.usuario u
            ORDER BY  v.fecha DESC
        ');
        $consulta->setMaxResults(500);

        return $consulta;
    }

    public function findTodasLasVentas()
    {
        return $this->queryTodasLasVentas()->getResult();
    }

    public function findVenta($idOffer, $idUser)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    v, o, u FROM OfertaBundle:Venta v
            JOIN      v.oferta o JOIN v.usuario u
            WHERE     v.oferta = :idOffer
            AND       v.usuario = :idUser
        ');
        $consulta->setParameters(array(
            'idOffer'   => $idOffer,
            'idUser'    => $idUser
        ));
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }
}
