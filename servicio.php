<?php
class Servicio {
    private $nombre;
    private $costo;
    private $descripcion;

    public function __construct($nombre, $costo, $descripcion) {
        $this->nombre = $nombre;
        $this->costo = $costo;
        $this->descripcion = $descripcion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCosto() {
        return $this->costo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCosto($costo) {
        $this->costo = $costo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}
?>
