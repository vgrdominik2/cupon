<?php

namespace Cupon\UsuarioBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Cupon\UsuarioBundle\Entity\Usuario
 *
 *
 * @ORM\Entity(repositoryClass="Cupon\UsuarioBundle\Entity\UsuarioRepository")
 * @DoctrineAssert\UniqueEntity("email")
 * @Assert\Callback(methods={"esDniValido"})
 */
class Usuario implements UserInterface
{
    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return array('ROLE_USUARIO');
    }

    public function getUsername()
    {
        return $this->getEmail();
    }
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
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotBlank(message = "Por favor, escribe tu nombre")
     */
    private $nombre;

    /**
     * @var string $apellidos
     *
     * @ORM\Column(name="apellidos", type="string", length=255)
     * @Assert\NotBlank(message = "Por favor, escribe tus apellidos")
     */
    private $apellidos;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(message = "Por favor, escribe tu e-mail.")
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(min = 6)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="text")
     */
    private $direccion;

    /**
     * @var boolean $permite_email
     *
     * @ORM\Column(name="permite_email", type="boolean")
     */
    private $permite_email;

    /**
     * @var \DateTime $fecha_alta
     *
     * @ORM\Column(name="fecha_alta", type="datetime")
     */
    private $fecha_alta;

    /**
     * @var \DateTime $fecha_nacimiento
     *
     * @ORM\Column(name="fecha_nacimiento", type="datetime")
     */
    private $fecha_nacimiento;

    /**
     * @var string $dni
     *
     * @ORM\Column(name="dni", type="string", length=9)
     */
    private $dni;

    /**
     * @var string $numero_targeta
     *
     * @ORM\Column(name="numero_targeta", type="string", length=20)
     */
    private $numero_targeta;

    /**
     * @ORM\ManyToOne(targetEntity="Cupon\CiudadBundle\Entity\Ciudad")
     * @Assert\NotBlank()
     */
    private $ciudad;

    public function __construct()
    {
        $this->fecha_alta = new \DateTime();
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
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Usuario
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set permite_email
     *
     * @param boolean $permiteEmail
     * @return Usuario
     */
    public function setPermiteEmail($permiteEmail)
    {
        $this->permite_email = $permiteEmail;
    
        return $this;
    }

    /**
     * Get permite_email
     *
     * @return boolean 
     */
    public function getPermiteEmail()
    {
        return $this->permite_email;
    }

    /**
     * Set fecha_alta
     *
     * @param \DateTime $fechaAlta
     * @return Usuario
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fecha_alta = $fechaAlta;
    
        return $this;
    }

    /**
     * Get fecha_alta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fecha_alta;
    }

    /**
     * Set fecha_nacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Usuario
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fecha_nacimiento = $fechaNacimiento;
    
        return $this;
    }

    /**
     * Get fecha_nacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    /**
     * Set dni
     *
     * @param string $dni
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set numero_targeta
     *
     * @param string $numeroTargeta
     * @return Usuario
     */
    public function setNumeroTargeta($numeroTargeta)
    {
        $this->numero_targeta = $numeroTargeta;
    
        return $this;
    }

    /**
     * Get numero_targeta
     *
     * @return string 
     */
    public function getNumeroTargeta()
    {
        return $this->numero_targeta;
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

    public function __toString()
    {
        return $this->getNombre().' '.$this->getApellidos();
    }

    public function esDniValido(ExecutionContext $context)
    {
        $dni = $this->getDni();

        if(0 === preg_match("/\d{1,8}[a-z]/i", $dni))
        {
            $context->addViolationAtSubPath('dni', 'El DNI introducio no
                tiene el formatio correcto (entre 1 y 8 números seguidos de
                una letram sin guines y sin dejar ningún espacio en blanco)',
                array(), null);

            $numero = substr($dni, 0, -1);
            $letra = strtoupper(substr($dni, -1));
            if ( $letra != substr("TRWAGMYFPDXBNJZSQVHLCKE", strtr($numero, "XYZ", "012")%23, 1))
            {
                $context->addViolationAtSubPath('dni', 'La letra no coincide con el
                    número del DNI. Comprueba que has escrito bién tanto el número como
                    la letra', array(), null);
            }
        }
    }

    /**
     * @Assert\True(message = "Debes tener al menos 18 años")
     */
    public function isMayorDeEdad()
    {
        return $this->fecha_nacimiento <= new \DateTime('today - 18 years');
    }
}