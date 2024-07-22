<?php

    require_once ('vehiculo.php');
    
    class ServiceVehiculo {
    
        private $vehiculos = [];
        private $servicioCliente;
    
        public function setServicioCliente($servicioCliente) {
            $this->servicioCliente = $servicioCliente;
        }
    
        private function vehiculoExiste($patente) {
            foreach ($this->vehiculos as $vehiculo) {
                if (strtolower($vehiculo->getPatente()) === strtolower($patente)) {
                    return true;
                }
            }
            return false;
        }
    
        public function altaVehiculo() {
            $dniCli = readline('Ingrese DNI Cliente: ');
            $patente = readline('Ingrese la patente del vehiculo: ');
    
            // Verificar si la patente ya existe
            if ($this->vehiculoExiste($patente)) {
                echo ('El vehículo con esta patente ya existe.' . PHP_EOL);
                return;
            }
    
            $modelo = readline('Ingrese modelo del Vehiculo: ');
            $marca = readline('Ingrese marca del Vehiculo: ');
    
            // Crear un nuevo vehículo
            $vehiculo = new Vehiculo($patente, $marca, $modelo);
    
            // Buscar el cliente por DNI
            $cliente = $this->servicioCliente->buscarClienteDni($dniCli);
    
            if ($cliente) {
                // Vincular el cliente al vehículo
                $vehiculo->setCliente($cliente);
    
                // Añadir el vehículo a la lista de vehículos del cliente
                $cliente->vincularAuto($patente);
    
                // Añadir el vehículo a la lista de vehículos
                $this->vehiculos[] = $vehiculo;
    
                echo ('El vehículo se ha cargado correctamente.' . PHP_EOL);
            } else {
                echo ('Cliente no encontrado. No se pudo vincular el vehículo.' . PHP_EOL);
            }
        }
    
        public function listaVehiculo() {
            // Función para mostrar vehículos
            foreach ($this->vehiculos as $vehiculo) {
                $vehiculo->mostrar();
            }
        }
    
        public function bajaVehiculo() {
            $patente = strtolower(readline('La patente del vehiculo a dar de baja es: '));
    
            foreach ($this->vehiculos as $vehiculoKey => $vehiculo) {
                if (strtolower($vehiculo->getPatente()) === $patente) {
                    // Obtener al cliente del vehículo
                    $cliente = $vehiculo->getCliente();
                    if ($cliente) {
                        // Desvincular el vehículo del cliente
                        $cliente->desvincularAuto($patente);
                    }
    
                    unset($this->vehiculos[$vehiculoKey]);
                    echo ('El vehículo se ha eliminado correctamente.' . PHP_EOL);
                    return true;
                }
            }
    
            echo ('Vehículo no encontrado.' . PHP_EOL);
            return false;
        }
    
        public function buscarVehiculo() {
            $patente = strtolower(readline('La patente a buscar: '));
            foreach ($this->vehiculos as $v) {
                if (strtolower($v->getPatente()) === $patente) {
                    echo ('Vehículo encontrado.' . PHP_EOL);
                    echo ('La patente del vehículo es: ' . $v->getPatente() . '; ');
                    echo ('La marca del vehículo es: ' . $v->getMarca() . '; ');
                    echo ('El modelo del vehículo es: ' . $v->getModelo() . '; ');
                    return $v;
                }
            }
    
            echo ('Vehículo no encontrado.' . PHP_EOL);
            return null;
        }
    
        public function modificarVehiculo() {
            $patente = strtolower(readline('La patente del vehiculo a modificar es: '));
            foreach ($this->vehiculos as $vehiculo) {
                if (strtolower($vehiculo->getPatente()) === $patente) {
                    $newpatente = readline('Nueva patente: ');
                    $newmarca = readline('Nueva marca: ');
                    $newmodelo = readline('Nuevo modelo: ');
    
                    $cliente = $vehiculo->getCliente();
                    if ($cliente) {
                        // Desvincular el vehículo antiguo del cliente
                        $cliente->desvincularAuto($patente);
                        // Vincular el vehículo modificado al cliente
                        $cliente->vincularAuto($newpatente);
                    }
    
                    $vehiculo->setPatente($newpatente);
                    $vehiculo->setMarca($newmarca);
                    $vehiculo->setModelo($newmodelo);
    
                    echo ('El vehículo se ha modificado exitosamente.' . PHP_EOL);
                    return true;
                }
            }
            echo ('El vehículo no existe.' . PHP_EOL);
            return false;
        }
   
        public function grabar() {
            $arrSerVeh = serialize($this->vehiculos);
            file_put_contents("vehiculos.json", $arrSerVeh);
        }
    
        public function leer() {
            $recArrVeh = file_get_contents("vehiculos.json");
            $arrOrigVeh = unserialize($recArrVeh);
            $this->vehiculos = $arrOrigVeh;
        }
    }
    