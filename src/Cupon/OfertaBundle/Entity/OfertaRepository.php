<?php
namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OfertaRepository extends EntityRepository
{
    public function findOfertaDelDia($ciudad)
    {
        $fechaPublicacion = new \DateTime;
        $fechaPublicacion->setTime(23, 59, 59);

        $em = $this->getEntityManager();

        $dql = 'SELECT    o, c, t
                FROM      OfertaBundle:Oferta o
                JOIN      o.ciudad c JOIN o.tienda t
                WHERE     o.revisada = true
                AND       o.fecha_publicacion < :fecha
                AND       c.slug = :ciudad
                ORDER BY  o.fecha_publicacion DESC';

        $consulta = $em->createQuery($dql);
        $consulta->setParameters(array(
            'fecha'     => $fechaPublicacion,
            'ciudad'    => $ciudad
        ));
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    public function findOferta($ciudad, $slug)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
            SELECT  o, c, t
            FROM    OfertaBundle:Oferta o
            JOIN    o.ciudad c JOIN o.tienda t
            WHERE   o.revisada = true
            AND     o.slug = :slug
            AND     c.slug = :ciudad
        ');

        $consulta->setParameters(array(
            'slug'      => $slug,
            'ciudad'    => $ciudad
        ));
        $consulta->setMaxResults(1);

        return $consulta->getOneOrNullResult();
    }

    public function findRelacionadas($ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    o, c
            FROM      OfertaBundle:Oferta o
            JOIN      o.ciudad c
            WHERE     o.revisada = true
            AND       o.fecha_publicacion <= :fecha
            AND       c.slug != :ciudad
            ORDER BY  o.fecha_publicacion DESC
        ');

        $consulta->setMaxResults(5);
        $consulta->setParameters(array(
            'ciudad' => $ciudad,
            'fecha' => new \DateTime('today')
        ));

        return $consulta->getResult();
    }

    public function findRecientes($ciudad_id)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    o, t
            FROM      OfertaBundle:Oferta o
            JOIN      o.tienda t
            WHERE     o.revisada = true
            AND       o.fecha_publicacion < :fecha
            AND       o.ciudad = :id
            ORDER BY  o.fecha_publicacion DESC
        ');

        $consulta->setMaxResults(5);
        $consulta->setParameters(array(
            'id' => $ciudad_id,
            'fecha' => new \DateTime('today')
        ));

        return $consulta->getResult();
    }

    public function findVentasByOferta($oferta)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT          v, o, u
            FROM            OfertaBundle:Venta v
            JOIN            v.oferta o
            JOIN            v.usuario u
            WHERE           o.id = :id
            ORDER BY        v.fecha DESC
        ');
        $consulta->setParameter('id', $oferta);

        return $consulta->getResult();
    }

    public  function queryTodasLasOfertas($ciudad)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT    o, t FROM OfertaBundle:Oferta o
            JOIN      o.tienda t JOIN o.ciudad c
            WHERE     c.slug = :ciudad
            ORDER BY  o.fecha_publicacion DESC
        ');
        $consulta->setParameter('ciudad', $ciudad);

        return $consulta;
    }

    public function findTodasLasOfertas($ciudad)
    {
        return $this->queryTodasLasOfertas($ciudad)->getResult();
    }
}
