<?php

require_once('Reserva.php');
class ServiceReserva {
    private $reservas = [];
    private $servicioCliente;
    private $servicioVehiculo;
    private $patentesReservadas = [];
    private $idCount = 1; // Contador para asignar IDs únicos a las reservas

    public function setServicioCliente($servicioCliente) {
        $this->servicioCliente = $servicioCliente;
    }

    public function setServicioVehiculo($servicioVehiculo) {
        $this->servicioVehiculo = $servicioVehiculo;
    }

    public function altaReserva() {
        $dniCliente = readline('Ingrese el DNI del cliente: ');
        $patente = readline('Ingrese la patente del vehículo: ');
    
        // Verificar si la patente ya está reservada
        if (in_array($patente, $this->patentesReservadas)) {
            echo ('La patente ya está reservada en otra reserva. No se pudo registrar la reserva.' . PHP_EOL);
            return;
        }
    
        // Buscar cliente y vehículo
        $cliente = $this->servicioCliente->buscarClienteDni($dniCliente);
        $vehiculo = $this->servicioVehiculo->buscarVehiculo($patente);
    
        // Si el cliente y el vehículo existen
        if ($cliente && $vehiculo) {
            echo 'Ingrese la fecha de la reserva en formato dd/mm/yyyy: ';
            $fechaIngresada = readline();
    
            echo 'Ingrese la hora de la reserva en formato hh:mm: ';
            $horaIngresada = readline();
    
            // Validar y formatear la fecha y hora ingresada
            $fechaHoraIngresada = $fechaIngresada . ' ' . $horaIngresada . ':00';
    
            // Convertir la fecha y hora ingresada al formato estándar de PHP
            $fecha = DateTime::createFromFormat('d/m/Y H:i:s', $fechaHoraIngresada);
    
            // Verificar si la conversión fue exitosa
            if (!$fecha) {
                echo ('Fecha y hora ingresada no válida. No se pudo registrar la reserva.' . PHP_EOL);
                return;
            }
    
            // Obtener la fecha en formato MySQL datetime
            $fechaMySQL = $fecha->format('Y-m-d H:i:s');
    
            // Verificar si la fecha y hora están ocupadas
            if ($this->esFechaOcupada(strtotime($fechaMySQL))) {
                echo ('La fecha y hora seleccionada ya está reservada. No se pudo registrar la reserva.' . PHP_EOL);
                return;
            }
    
            // Agregar la patente a la lista de patentes reservadas
            $this->patentesReservadas[] = $patente;
    
            $descripcion = readline('Descripción de la reserva: ');
    
            // Crear la reserva y añadirla a la lista de reservas
            $reserva = new Reserva($this->idCount, $cliente, $vehiculo, $fechaMySQL, $descripcion);
            $this->reservas[] = $reserva;
            $this->idCount++;
    
            // Mostrar mensaje de confirmación
            echo ('La reserva se ha registrado correctamente. ID de la reserva: ' . $reserva->getId() . PHP_EOL);
            echo ('Fecha y hora de la reserva: ' . $fecha->format('d/m/Y H:i:s') . PHP_EOL);
        } else {
            // Si cliente o vehículo no existen
            echo ('Cliente o vehículo no encontrado. No se pudo registrar la reserva.' . PHP_EOL);
        }
    }
    
    private function esFechaOcupada($timestampIngresado) {
        // Verificar si la fecha y hora ya está ocupada en una reserva existente
        foreach ($this->reservas as $reserva) {
            $timestampReserva = strtotime($reserva->getFecha());
            if ($timestampReserva === $timestampIngresado) {
                return true; // Fecha y hora ocupada
            }
        }
        return false; // Fecha y hora disponible
    }
    
    public function bajaReservaPorId($id) {
        $indice = $this->buscarReservaPorId($id);
        if ($indice !== false) {
            unset($this->reservas[$indice]);
            echo ('La reserva con ID ' . $id . ' se ha eliminado correctamente.' . PHP_EOL);
        } else {
            echo ('Reserva no encontrada con el ID ' . $id . PHP_EOL);
        }
    }

    private function buscarReservaPorId($id) {
        foreach ($this->reservas as $indice => $reserva) {
            if ($reserva->getId() == $id) {
                return $indice;
            }
        }
        return false;
    }

    private function buscarReservaPorPatente($patente) {
        foreach ($this->reservas as $indice => $reserva) {
            if ($reserva->getVehiculo()->getPatente() == $patente) {
                return $indice;
            }
        }
        return false;
    }

    public function listaReservas() {
        foreach ($this->reservas as $reserva) {
            $cliente = $reserva->getCliente();
            $vehiculo = $reserva->getVehiculo();
            
            echo('Id: ' . $reserva->getId() . PHP_EOL);
            echo('Cliente: ' . $cliente->getNombre() . ' ' . $cliente->getApellido() . PHP_EOL);
            echo('DNI del Cliente: ' . $cliente->getDni() . PHP_EOL);
            echo('Vehículo: ' . $vehiculo->getMarca() . ' ' . $vehiculo->getModelo() . PHP_EOL);
            echo('Patente: ' . $vehiculo->getPatente() . PHP_EOL);
            echo('Fecha de la Reserva: ' . $reserva->getFecha() . PHP_EOL);
            echo('Descripción de la Reserva: ' . $reserva->getDescripcion() . PHP_EOL);
            echo(PHP_EOL);
        }
    }

    public function modificarReserva() {
        $patente = readline('Ingrese la patente del vehículo: ');
        $indice = $this->buscarReservaPorPatente($patente);

        if ($indice !== false) {
            $reserva = $this->reservas[$indice];

            echo ('Reserva encontrada. Puede realizar modificaciones a continuación.' . PHP_EOL);

            $descripcion = readline('Ingrese la nueva descripción de la reserva: ');
            $fecha = readline('Ingrese la nueva fecha de la reserva: ');
            $reserva->setDescripcion($descripcion);
            $reserva->setFecha($fecha);

            echo ('La reserva se ha modificado correctamente.' . PHP_EOL);
        } else {
            echo ('No se encontraron reservas para la patente ' . $patente . PHP_EOL);
        }
    }
}
