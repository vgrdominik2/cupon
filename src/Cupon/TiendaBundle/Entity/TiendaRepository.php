<?php
namespace Cupon\TiendaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TiendaRepository extends EntityRepository
{
    public function findUltimasOfertasPublicadas($tienda_id, $limite = 10)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    o, t
            FROM      OfertaBundle:Oferta o
            JOIN      o.tienda t
            WHERE     o.revisada = true
            AND       o.fecha_publicacion < :fecha
            AND       o.tienda = :id
            ORDER BY  o.fecha_expiracion DESC
        ');
        $consulta->setMaxResults($limite);
        $consulta->setParameters(array(
            'id'    => $tienda_id,
            'fecha' => new \DateTime('now')
        ));

        return $consulta->getResult();
    }

    public function findCercanas($tienda, $ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    t, c
            FROM      TiendaBundle:Tienda t
            JOIN      t.ciudad c
            WHERE     c.slug = :ciudad
            AND       t.slug != :tienda
        ');
        $consulta->setMaxResults(5);
        $consulta->setParameters(array(
            'ciudad'    => $ciudad,
            'tienda'    => $tienda
        ));

        return $consulta->getResult();
    }

    public function findOfertasRecientes($tienda_id, $limite = null)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT      o, t
            FROM        OfertaBundle:Oferta o
            JOIN        o.tienda t
            WHERE       o.tienda = :id
            ORDER BY    o.fecha_expiracion DESC
        ');
        $consulta->setParameter('id', $tienda_id);

        if (null != $limite)
        {
            $consulta->setMaxResults($limite);
        }

        return $consulta->getResult();
    }
}