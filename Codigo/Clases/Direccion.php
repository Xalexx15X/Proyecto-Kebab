<?php

class Direccion
{
    private $id;
    private $nombre_calle;
    private $n_calle;
    private $tipo_casa;
    private $n_casa;

    public function __construct($id, $nombre_calle, $n_calle, $tipo_casa, $n_casa)
    {
        $this->id = $id;
        $this->nombre_calle = $nombre_calle;
        $this->n_calle = $n_calle;
        $this->tipo_casa = $tipo_casa;
        $this->n_casa = $n_casa;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre_calle() {
        return $this->nombre_calle;
    }

    public function setNombre_calle($nombre_calle) {
        $this->nombre_calle = $nombre_calle;
    }

    public function getN_calle() {
        return $this->n_calle;
    }

    public function setN_calle($n_calle) {
        $this->n_calle = $n_calle;
    }

    public function getTipo_casa() {
        return $this->tipo_casa;
    }

    public function setTipo_casa($tipo_casa) {
        $this->tipo_casa = $tipo_casa;
    }

    public function getN_casa() {
        return $this->n_casa;
    }

    public function setN_casa($n_casa) {
        $this->n_casa = $n_casa;
    }

    public function __toString() {
        return "Direccion: ID={$this->id}, Nombre={$this->nombre_calle}, Calle={$this->n_calle}, Tipo={$this->tipo_casa}, Casa={$this->n_casa}";
    }
}   