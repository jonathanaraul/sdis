<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proyecto
 *
 * @ORM\Table(name="proyecto")
 * @ORM\Entity
 */
class Proyecto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=300, nullable=false)
     */
    private $nombre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="langelier", type="boolean", nullable=false)
     */
    private $langelier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rysnar", type="boolean", nullable=false)
     */
    private $rysnar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="puckorius", type="boolean", nullable=false)
     */
    private $puckorius;

    /**
     * @var string
     *
     * @ORM\Column(name="informacion", type="text", nullable=false)
     */
    private $informacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="autoguardado", type="boolean", nullable=false)
     */
    private $autoguardado;

    /**
     * @var float
     *
     * @ORM\Column(name="ph", type="float", nullable=false)
     */
    private $ph;

    /**
     * @var float
     *
     * @ORM\Column(name="tds", type="float", nullable=false)
     */
    private $tds;

    /**
     * @var float
     *
     * @ORM\Column(name="t", type="float", nullable=false)
     */
    private $t;

    /**
     * @var float
     *
     * @ORM\Column(name="ca2", type="float", nullable=false)
     */
    private $ca2;

    /**
     * @var float
     *
     * @ORM\Column(name="alcalinidad", type="float", nullable=false)
     */
    private $alcalinidad;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;


    public function getRespuesta($valor)
    {
	return (($valor != '1') ? 'No' : 'Si');
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Proyecto
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Proyecto
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
     * Set langelier
     *
     * @param boolean $langelier
     * @return Proyecto
     */
    public function setLangelier($langelier)
    {
        $this->langelier = $langelier;
    
        return $this;
    }

    /**
     * Get langelier
     *
     * @return boolean 
     */
    public function getLangelier()
    {
        return $this->langelier;
    }

    /**
     * Set rysnar
     *
     * @param boolean $rysnar
     * @return Proyecto
     */
    public function setRysnar($rysnar)
    {
        $this->rysnar = $rysnar;
    
        return $this;
    }

    /**
     * Get rysnar
     *
     * @return boolean 
     */
    public function getRysnar()
    {
        return $this->rysnar;
    }

    /**
     * Set puckorius
     *
     * @param boolean $puckorius
     * @return Proyecto
     */
    public function setPuckorius($puckorius)
    {
        $this->puckorius = $puckorius;
    
        return $this;
    }

    /**
     * Get puckorius
     *
     * @return boolean 
     */
    public function getPuckorius()
    {
        return $this->puckorius;
    }

    /**
     * Set informacion
     *
     * @param string $informacion
     * @return Proyecto
     */
    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
    
        return $this;
    }

    /**
     * Get informacion
     *
     * @return string 
     */
    public function getInformacion()
    {
        return $this->informacion;
    }

    /**
     * Set autoguardado
     *
     * @param boolean $autoguardado
     * @return Proyecto
     */
    public function setAutoguardado($autoguardado)
    {
        $this->autoguardado = $autoguardado;
    
        return $this;
    }

    /**
     * Get autoguardado
     *
     * @return boolean 
     */
    public function getAutoguardado()
    {
        return $this->autoguardado;
    }

    /**
     * Set ph
     *
     * @param float $ph
     * @return Proyecto
     */
    public function setPh($ph)
    {
        $this->ph = $ph;
    
        return $this;
    }

    /**
     * Get ph
     *
     * @return float 
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * Set tds
     *
     * @param float $tds
     * @return Proyecto
     */
    public function setTds($tds)
    {
        $this->tds = $tds;
    
        return $this;
    }

    /**
     * Get tds
     *
     * @return float 
     */
    public function getTds()
    {
        return $this->tds;
    }

    /**
     * Set t
     *
     * @param float $t
     * @return Proyecto
     */
    public function setT($t)
    {
        $this->t = $t;
    
        return $this;
    }

    /**
     * Get t
     *
     * @return float 
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * Set ca2
     *
     * @param float $ca2
     * @return Proyecto
     */
    public function setCa2($ca2)
    {
        $this->ca2 = $ca2;
    
        return $this;
    }

    /**
     * Get ca2
     *
     * @return float 
     */
    public function getCa2()
    {
        return $this->ca2;
    }

    /**
     * Set alcalinidad
     *
     * @param float $alcalinidad
     * @return Proyecto
     */
    public function setAlcalinidad($alcalinidad)
    {
        $this->alcalinidad = $alcalinidad;
    
        return $this;
    }

    /**
     * Get alcalinidad
     *
     * @return float 
     */
    public function getAlcalinidad()
    {
        return $this->alcalinidad;
    }

    /**
     * Set user
     *
     * @param \Proyecto\PrincipalBundle\Entity\User $user
     * @return Proyecto
     */
    public function setUser(\Proyecto\PrincipalBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Proyecto\PrincipalBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}