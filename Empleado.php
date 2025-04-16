<?php

class Empleado {
    const SMLV = 1600000;
    private $conn;
    private $id;
    private $nombre;
    private $salarioBase;
    private $horasExtraDia;
    private $horasExtraNoche;
    private $horasExtraFestivas;
    private $nivelRiesgo;

    public function __construct($conn, $nombre, $salarioBase, $horasExtraDia = 0, $horasExtraNoche = 0, $horasExtraFestivas = 0, $nivelRiesgo = 1) {
        $this->conn = $conn;
        $this->nombre = $nombre;
        $this->salarioBase = $salarioBase;
        $this->horasExtraDia = $horasExtraDia;
        $this->horasExtraNoche = $horasExtraNoche;
        $this->horasExtraFestivas = $horasExtraFestivas;
        $this->nivelRiesgo = $nivelRiesgo;
    }

    public function guardar() {
        $sql = "INSERT INTO empleados (nombre, salario_base, horas_extra_dia, horas_extra_noche, horas_extra_festivas, nivel_riesgo)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdiiii", $this->nombre, $this->salarioBase, $this->horasExtraDia, $this->horasExtraNoche, $this->horasExtraFestivas, $this->nivelRiesgo);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
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
        return $salud + pension + arl;
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
