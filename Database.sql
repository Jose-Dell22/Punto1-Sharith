CREATE DATABASE IF NOT EXISTS nomina;

USE Nombredelabasededatos;

CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    salario_base DOUBLE,
    horas_extra_dia INT,
    horas_extra_noche INT,
    horas_extra_festivas INT,
    nivel_riesgo INT
);
