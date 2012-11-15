<?php

namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cupon\OfertaBundle\Util\Util;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cupon\OfertaBundle\Entity\Oferta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cupon\OfertaBundle\Entity\OfertaRepository")
 */
class Oferta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Gedmo\Translatable
     *
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     * @Gedmo\Translatable
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 30)
     */
    private $descripcion;

    /**
     * @var string $condiciones
     *
     * @ORM\Column(name="condiciones", type="text")
     */
    private $condiciones;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     *
     * @Assert\Image(maxSize = "500k")
     */
    private $foto;

    /**
     * @var float $precio
     *
     * @ORM\Column(name="precio", type="decimal")
     *
     * @Assert\Range(min = 0)
     */
    private $precio;

    /**
     * @var float $descuento
     *
     * @ORM\Column(name="descuento", type="decimal")
     */
    private $descuento;

    /**
     * @var \DateTime $fecha_publicacion
     *
     * @ORM\Column(name="fecha_publicacion", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $fecha_publicacion;

    /**
     * @var \DateTime $fecha_expiracion
     *
     * @ORM\Column(name="fecha_expiracion", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $fecha_expiracion;

    /**
     * @var integer $compras
     *
     * @ORM\Column(name="compras", type="integer")
     */
    private $compras;

    /**
     * @var integer $umbral
     *
     * @ORM\Column(name="umbral", type="integer")
     *
     * @Assert\Range(min = 0)
     */
    private $umbral;

    /**
     * @var boolean $revisada
     *
     * @ORM\Column(name="revisada", type="boolean", nullable=true)
     */
    private $revisada;

    /**
     * @ORM\ManyToOne(targetEntity="Cupon\CiudadBundle\Entity\Ciudad")
     */
    private $ciudad;

    /**
     * @ORM\ManyToOne(targetEntity="Cupon\TiendaBundle\Entity\Tienda")
     */
    private $tienda;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Oferta
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->slug = Util::getSlug($nombre);
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Oferta
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Oferta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set condiciones
     *
     * @param string $condiciones
     * @return Oferta
     */
    public function setCondiciones($condiciones)
    {
        $this->condiciones = $condiciones;
    
        return $this;
    }

    /**
     * Get condiciones
     *
     * @return string 
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Oferta
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return Oferta
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set descuento
     *
     * @param float $descuento
     * @return Oferta
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    
        return $this;
    }

    /**
     * Get descuento
     *
     * @return float 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set fecha_publicacion
     *
     * @param \DateTime $fechaPublicacion
     * @return Oferta
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fecha_publicacion = $fechaPublicacion;
    
        return $this;
    }

    /**
     * Get fecha_publicacion
     *
     * @return \DateTime 
     */
    public function getFechaPublicacion()
    {
        return $this->fecha_publicacion;
    }

    /**
     * Set fecha_expiracion
     *
     * @param \DateTime $fechaExpiracion
     * @return Oferta
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fecha_expiracion = $fechaExpiracion;
    
        return $this;
    }

    /**
     * Get fecha_expiracion
     *
     * @return \DateTime 
     */
    public function getFechaExpiracion()
    {
        return $this->fecha_expiracion;
    }

    /**
     * Set compras
     *
     * @param integer $compras
     * @return Oferta
     */
    public function setCompras($compras)
    {
        $this->compras = $compras;
    
        return $this;
    }

    /**
     * Get compras
     *
     * @return integer 
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * Set umbral
     *
     * @param integer $umbral
     * @return Oferta
     */
    public function setUmbral($umbral)
    {
        $this->umbral = $umbral;
    
        return $this;
    }

    /**
     * Get umbral
     *
     * @return integer 
     */
    public function getUmbral()
    {
        return $this->umbral;
    }

    /**
     * Set revisada
     *
     * @param boolean $revisada
     * @return Oferta
     */
    public function setRevisada($revisada)
    {
        $this->revisada = $revisada;
    
        return $this;
    }

    /**
     * Get revisada
     *
     * @return boolean 
     */
    public function getRevisada()
    {
        return $this->revisada;
    }

    public function setCiudad(\Cupon\CiudadBundle\Entity\Ciudad $ciudad)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function setTienda(\Cupon\TiendaBundle\Entity\Tienda $tienda)
    {
        $this->tienda = $tienda;
    
        return $this;
    }

    public function getTienda()
    {
        return $this->tienda;
    }

    public function subirFoto($directorioDestino)
    {
        if( null == $this->foto )
        {
            return;
        }

        $nombreArchivoFoto = uniqid('cupon-').'-foto1.jpg';

        $this->foto->move($directorioDestino, $nombreArchivoFoto);

        $this->setFoto($nombreArchivoFoto);
    }

    public function isFechaValida()
    {
        if ($this->fecha_publicacion == null
            || $this->fecha_expiracion == null)
        {
            return true;
        }

        return $this->fecha_expiracion > $this->fecha_publicacion;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}