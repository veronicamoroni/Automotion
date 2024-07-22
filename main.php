<?php
require_once('ServiceCliente.php');
require_once('Cliente.php');
require_once('ServiceVehiculo.php');
require_once('Vehiculo.php');
require_once('Reserva.php');
require_once('ServiceReserva.php');


// Instanciar servicios
$servicioCliente = new ServiceCliente();
$servicioVehiculo = new ServiceVehiculo();
$servicioVehiculo->setServicioCliente($servicioCliente);
$servicioReserva = new ServiceReserva();
$servicioReserva->setServicioCliente($servicioCliente);
$servicioReserva->setServicioVehiculo($servicioVehiculo);

// Función para mostrar el menú principal
function menuPrincipal() {
    echo "========= Bienvenidos =========\n";
    echo "===== PosService AutoMotion ====\n";
    echo "===============================\n";
    echo "Menu de Inicio\n";
    echo "===============================\n";
    echo "1-Clientes\n";
    echo "2-Vehículos\n";
    echo "3-Reservas\n";
    echo "4-Facturacion\n";
    echo "0-Salir\n";
}

// Función para mostrar el menú de clientes
function menuCliente() {
    echo "===============================\n";
    echo "Menu de Clientes\n";
    echo "===============================\n";
    echo "1 - Alta Cliente\n";
    echo "2 - Modificar Cliente\n";
    echo "3 - Baja Cliente\n";
    echo "4 - Buscar Cliente\n";
    echo "5 - Listar Clientes\n";
    echo "6 - Listar Clientes/vehiculos\n";
    echo "0 - Regresar al Menú Principal\n";
}

// Función para mostrar el menú de vehículos
function menuVehiculo() {
    echo "===============================\n";
    echo "Menu de Vehículos\n";
    echo "===============================\n";
    echo "1 - Alta Vehículo\n";
    echo "2 - Modificar Vehículo\n";
    echo "3 - Baja Vehículo\n";
    echo "4 - Buscar Vehiculo\n";
    echo "5 - Listar Vehiculos\n";
    echo "0 - Regresar al Menú Principal\n";
}

// Función para mostrar el menú de reservas
function menuReserva() {
    echo "===============================\n";
    echo "Menu de Reservas\n";
    echo "===============================\n";
    echo "1 - Alta Reserva\n";
    echo "2 - Modificar Reserva\n";
    echo "3 - Baja Reserva\n";
    echo "4 - Listar Reservas\n";
    echo "0 - Regresar al Menú Principal\n";
}

// Función para mostrar el menú de facturación
function menuFacturacion() {
    echo "===============================\n";
    echo "Menú Facturación\n";
    echo "===============================\n";
    echo "1 - Confeccionar Factura\n";
    echo "2 - Mostrar Factura\n";
    echo "3 - Eliminar Factura\n";
    echo "0 - Salir\n";
}

// Ciclo principal del programa
$opcion = " ";
while ($opcion !== '0') {
    menuPrincipal();
    $opcion = readline('Ingrese una opción: ');

    switch ($opcion) {
        case '1':
            echo "Seleccionaste Menú de clientes.\n";
            $opcionC = "";
            while ($opcionC !== '0') {
                menuCliente();
                $opcionC = readline('Ingrese una opción: ');
                switch ($opcionC) {
                    case '1': 
                        echo "Seleccionaste dar de alta a un cliente.\n";
                        $servicioCliente->altaCliente(); break;
                    
                    case '2': 
                        echo "Seleccionaste modificar un cliente.\n";
                        $servicioCliente->modificarCliente(); break;
                    
                    case '3': 
                        echo "Seleccionaste dar de baja a un cliente.\n";
                        $servicioCliente->bajaCliente(); break;
                    
                    case '4': 
                        echo "Seleccionaste buscar un cliente.\n";
                        $servicioCliente->buscarCliente(); break;
                    
                    case '5': 
                        echo "Lista de clientes.\n";
                        $servicioCliente->listaCliente(); break;
                    
                    case '6': 
                        echo "Lista de clientes con sus vehículos.\n";
                        $servicioCliente->listarClientesConVehiculos(); break;
                    
                    case '0': 
                        $servicioCliente->grabar();
                        echo "Regresar al Menú Principal.\n"; break;
                    
                    default: 
                        echo "Opción inválida.\n";
                }
            }
            break;

        case '2': 
            echo "Seleccionaste Menú de vehículos.\n";
            $opcionV = "";
            while ($opcionV !== '0') {
                menuVehiculo();
                $opcionV = readline('Ingrese una opción: ');
                switch ($opcionV) {
                    case '1': 
                        echo "Seleccionaste dar de alta a un vehículo.\n";
                        $servicioVehiculo->altaVehiculo(); break;
                    
                    case '2': 
                        echo "Seleccionaste modificar un vehículo.\n";
                        $servicioVehiculo->modificarVehiculo(); break;
                    
                    case '3': 
                        echo "Seleccionaste dar de baja a un vehículo.\n";
                        $servicioVehiculo->bajaVehiculo(); break;
                    
                    case '4': 
                        echo "Seleccionaste buscar un vehículo.\n";
                        $servicioVehiculo->buscarVehiculo(); break;
                    
                    case '5': 
                        echo "Seleccionaste lista de vehículos.\n";
                        $servicioVehiculo->listaVehiculo(); break;
                    
                    case '0': 
                        $servicioVehiculo->grabar();
                        echo "Regresar al Menú Principal.\n"; break;
                    
                    default: 
                        echo "Opción inválida.\n";
                }
            }
            break;

        case '3':
            $opcionR = "";
            while ($opcionR !== '0') {
                menuReserva();
                $opcionR = readline('Ingrese una opción: ');
                switch ($opcionR) {
                    case '1': 
                        echo "Seleccionaste dar de alta una reserva.\n";
                        $servicioReserva->altaReserva(); break;
                    
                    case '2': 
                        echo "Seleccionaste modificar una reserva.\n";
                        $servicioReserva->modificarReserva(); break;
                    
                    case '3': 
                        echo "Seleccionaste dar de baja una reserva.\n";
                        $idReserva = readline('Ingrese el ID de la reserva a dar de baja: ');
                        if (is_numeric($idReserva)) {
                            $servicioReserva->bajaReservaPorId(intval($idReserva));
                        } else {
                            echo "ID de reserva no válido. Debes ingresar un número.\n";
                        }
                        break;
                    
                    case '4': 
                        echo "Lista de reservas.\n";
                        $servicioReserva->listaReservas(); break;
                    
                    case '0': 
                        echo "Regresar al Menú Principal.\n"; break;
                    
                    default: 
                        echo "Opción inválida.\n";
                }
            }
            break;

        
           

        case '0': 
            $servicioCliente->salida();
            break;

        default: 
            echo "Opción inválida.\n";
            break;
    }
}



    