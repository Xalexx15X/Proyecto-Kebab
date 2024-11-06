<?php

class Direccion
{
    private $id;
    private $nombre;
    private $n_calle;
    private $tipo;
    private $n_casa;

    public function __construct($id, $nombre, $n_calle, $tipo, $n_casa)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->n_calle = $n_calle;
        $this->tipo = $tipo;
        $this->n_casa = $n_casa;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNCalle()
    {
        return $this->n_calle;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getNCasa()
    {
        return $this->n_casa;
    }       

    public function __toString()
    {
        return "Direccion: ID={$this->id}, Nombre={$this->nombre}, Calle={$this->n_calle}, Tipo={$this->tipo}, Casa={$this->n_casa}";
    }

}   