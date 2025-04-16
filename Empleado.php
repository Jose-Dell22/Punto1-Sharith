<?php

class Empleado {
    const SMLV = 1600000;

    private $id;
    private $nombre;
    private $salarioBase;
    private $horasExtraDia;
    private $horasExtraNoche;
    private $horasExtraFestivas;
    private $nivelRiesgo;

    public function __construct($nombre, $salarioBase, $horasExtraDia = 0, $horasExtraNoche = 0, $horasExtraFestivas = 0, $nivelRiesgo = 1) {
        $this->nombre = $nombre;
        $this->salarioBase = $salarioBase;
        $this->horasExtraDia = $horasExtraDia;
        $this->horasExtraNoche = $horasExtraNoche;
        $this->horasExtraFestivas = $horasExtraFestivas;
        $this->nivelRiesgo = $nivelRiesgo;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getSalarioBase() {
        return $this->salarioBase;
    }

    public function getHorasExtraDia() {
        return $this->horasExtraDia;
    }

    public function getHorasExtraNoche() {
        return $this->horasExtraNoche;
    }

    public function getHorasExtraFestivas() {
        return $this->horasExtraFestivas;
    }

    public function getNivelRiesgo() {
        return $this->nivelRiesgo;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setSalarioBase($salarioBase) {
        $this->salarioBase = $salarioBase;
    }

    public function setHorasExtraDia($horasExtraDia) {
        $this->horasExtraDia = $horasExtraDia;
    }

    public function setHorasExtraNoche($horasExtraNoche) {
        $this->horasExtraNoche = $horasExtraNoche;
    }

    public function setHorasExtraFestivas($horasExtraFestivas) {
        $this->horasExtraFestivas = $horasExtraFestivas;
    }

    public function setNivelRiesgo($nivelRiesgo) {
        $this->nivelRiesgo = $nivelRiesgo;
    }

    
    private function calcularSubsidio() {
        return $this->salarioBase < 2 * self::SMLV ? self::SMLV : 0;
    }

    private function calcularValorHora() {
        return $this->salarioBase / 240;
    }

    private function calcularExtras() {
        $valorHora = $this->calcularValorHora();
        return $this->horasExtraDia * $valorHora * 1.25 +
               $this->horasExtraNoche * $valorHora * 1.75 +
               $this->horasExtraFestivas * $valorHora * 2.0;
    }

    private function calcularDeducciones() {
        $salud = $this->salarioBase * 0.04;
        $pension = $this->salarioBase * 0.04;
        $arl = $this->salarioBase * $this->obtenerPorcentajeARL();
        return $salud + $pension + $arl;
    }

    private function obtenerPorcentajeARL() {
        $niveles = [
            1 => 0.00522,
            2 => 0.01044,
            3 => 0.02436,
            4 => 0.04350,
            5 => 0.06960
        ];
        return $niveles[$this->nivelRiesgo] ?? 0.00522;
    }

    public function calcularPagoTotal() {
        return $this->salarioBase + $this->calcularSubsidio() + $this->calcularExtras() - $this->calcularDeducciones();
    }

    public function imprimirResumenPago() {
        echo "Empleado: {$this->nombre}<br>";
        echo "Pago Total: $" . number_format($this->calcularPagoTotal(), 2, ',', '.') . "<br>";
    }
}
