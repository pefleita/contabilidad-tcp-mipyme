<?php

namespace Database\Seeders;

use App\Models\ActivoFijo;
use Illuminate\Database\Seeder;

class ActivoFijoSeeder extends Seeder
{
    public function run(): void
    {
        $empresaId = 1;

        $activos = [
            // Equipos de cómputo (8)
            ['codigo' => 'EQ-001', 'nombre' => 'Servidor HP ProLiant DL380', 'tipo' => 'equipo', 'costo_original' => 145000.00, 'valor_residual' => 5000.00, 'vida_util_anos' => 5, 'fecha_adquisicion' => '2024-01-15', 'fecha_inicio_depreciacion' => '2024-02-01', 'descripcion' => 'Servidor principal para sistemas contables'],
            ['codigo' => 'EQ-002', 'nombre' => 'Workstation Dell Precision T5820', 'tipo' => 'equipo', 'costo_original' => 85000.00, 'valor_residual' => 3000.00, 'vida_util_anos' => 4, 'fecha_adquisicion' => '2024-03-10', 'fecha_inicio_depreciacion' => '2024-04-01', 'descripcion' => 'Estación de trabajo para diseño y análisis'],
            ['codigo' => 'EQ-003', 'nombre' => 'Laptop ThinkPad X1 Carbon Gen 11', 'tipo' => 'equipo', 'costo_original' => 62000.00, 'valor_residual' => 2000.00, 'vida_util_anos' => 4, 'fecha_adquisicion' => '2024-05-20', 'fecha_inicio_depreciacion' => '2024-06-01'],
            ['codigo' => 'EQ-004', 'nombre' => 'Monitor Samsung 32" 4K UHD', 'tipo' => 'equipo', 'costo_original' => 18500.00, 'valor_residual' => 500.00, 'vida_util_anos' => 5, 'fecha_adquisicion' => '2024-02-01', 'fecha_inicio_depreciacion' => '2024-03-01'],
            ['codigo' => 'EQ-005', 'nombre' => 'Impresora Multifuncional Brother MFC-L8900', 'tipo' => 'equipo', 'costo_original' => 32000.00, 'valor_residual' => 1000.00, 'vida_util_anos' => 3, 'fecha_adquisicion' => '2024-04-15', 'fecha_inicio_depreciacion' => '2024-05-01'],
            ['codigo' => 'EQ-006', 'nombre' => 'Switch Cisco Catalyst 9200', 'tipo' => 'equipo', 'costo_original' => 42000.00, 'valor_residual' => 1500.00, 'vida_util_anos' => 6, 'fecha_adquisicion' => '2024-01-20', 'fecha_inicio_depreciacion' => '2024-02-01'],
            ['codigo' => 'EQ-007', 'nombre' => 'NAS Synology DS1823xs+', 'tipo' => 'equipo', 'costo_original' => 38000.00, 'valor_residual' => 2000.00, 'vida_util_anos' => 5, 'fecha_adquisicion' => '2024-06-10', 'fecha_inicio_depreciacion' => '2024-07-01', 'descripcion' => 'Almacenamiento en red para backups'],
            ['codigo' => 'EQ-008', 'nombre' => 'UPS APC Smart-UPS 3000VA', 'tipo' => 'equipo', 'costo_original' => 15000.00, 'valor_residual' => 500.00, 'vida_util_anos' => 4, 'fecha_adquisicion' => '2024-01-25', 'fecha_inicio_depreciacion' => '2024-02-01'],

            // Muebles (6)
            ['codigo' => 'MB-001', 'nombre' => 'Escritorio Ejecutivo Roble', 'tipo' => 'mueble', 'costo_original' => 12500.00, 'valor_residual' => 500.00, 'vida_util_anos' => 10, 'fecha_adquisicion' => '2023-11-01', 'fecha_inicio_depreciacion' => '2023-12-01'],
            ['codigo' => 'MB-002', 'nombre' => 'Silla Ergonómica Herman Miller Aeron', 'tipo' => 'mueble', 'costo_original' => 28000.00, 'valor_residual' => 2000.00, 'vida_util_anos' => 8, 'fecha_adquisicion' => '2023-11-01', 'fecha_inicio_depreciacion' => '2023-12-01'],
            ['codigo' => 'MB-003', 'nombre' => 'Estantería Metálica 5 niveles', 'tipo' => 'mueble', 'costo_original' => 8500.00, 'valor_residual' => 300.00, 'vida_util_anos' => 10, 'fecha_adquisicion' => '2024-02-15', 'fecha_inicio_depreciacion' => '2024-03-01'],
            ['codigo' => 'MB-004', 'nombre' => 'Mesa de Conferencias 8 puestos', 'tipo' => 'mueble', 'costo_original' => 22000.00, 'valor_residual' => 1000.00, 'vida_util_anos' => 10, 'fecha_adquisicion' => '2023-12-01', 'fecha_inicio_depreciacion' => '2024-01-01'],
            ['codigo' => 'MB-005', 'nombre' => 'Sillones Sala de Espera (juego 4)', 'tipo' => 'mueble', 'costo_original' => 16000.00, 'valor_residual' => 500.00, 'vida_util_anos' => 7, 'fecha_adquisicion' => '2024-03-01', 'fecha_inicio_depreciacion' => '2024-04-01'],
            ['codigo' => 'MB-006', 'nombre' => 'Archivador Metálico 4 gavetas', 'tipo' => 'mueble', 'costo_original' => 7200.00, 'valor_residual' => 200.00, 'vida_util_anos' => 12, 'fecha_adquisicion' => '2023-10-15', 'fecha_inicio_depreciacion' => '2023-11-01'],

            // Vehículos (4)
            ['codigo' => 'VE-001', 'nombre' => 'Toyota Corolla 2024', 'tipo' => 'vehiculo', 'costo_original' => 385000.00, 'valor_residual' => 45000.00, 'vida_util_anos' => 8, 'fecha_adquisicion' => '2024-01-10', 'fecha_inicio_depreciacion' => '2024-02-01', 'descripcion' => 'Vehículo de representación y gestiones comerciales'],
            ['codigo' => 'VE-002', 'nombre' => 'Hyundai H-1 Cargo (furgón)', 'tipo' => 'vehiculo', 'costo_original' => 295000.00, 'valor_residual' => 30000.00, 'vida_util_anos' => 8, 'fecha_adquisicion' => '2024-03-20', 'fecha_inicio_depreciacion' => '2024-04-01', 'descripcion' => 'Vehículo para transporte de mercancía'],
            ['codigo' => 'VE-003', 'nombre' => 'Moto Eléctrica Silence S01', 'tipo' => 'vehiculo', 'costo_original' => 42000.00, 'valor_residual' => 5000.00, 'vida_util_anos' => 4, 'fecha_adquisicion' => '2024-05-05', 'fecha_inicio_depreciacion' => '2024-06-01', 'descripcion' => 'Transporte ecológico para mensajería'],
            ['codigo' => 'VE-004', 'nombre' => 'Bicicleta Eléctrica Mondraker', 'tipo' => 'vehiculo', 'costo_original' => 18500.00, 'valor_residual' => 2000.00, 'vida_util_anos' => 3, 'fecha_adquisicion' => '2024-07-01', 'fecha_inicio_depreciacion' => '2024-08-01'],

            // Inmuebles (4)
            ['codigo' => 'IN-001', 'nombre' => 'Local Comercial Centro Habana', 'tipo' => 'inmueble', 'costo_original' => 2500000.00, 'valor_residual' => 250000.00, 'vida_util_anos' => 30, 'fecha_adquisicion' => '2020-06-01', 'fecha_inicio_depreciacion' => '2020-07-01', 'descripcion' => 'Local principal de atención al cliente y oficinas'],
            ['codigo' => 'IN-002', 'nombre' => 'Almacén Zona Industrial', 'tipo' => 'inmueble', 'costo_original' => 1800000.00, 'valor_residual' => 180000.00, 'vida_util_anos' => 30, 'fecha_adquisicion' => '2021-03-15', 'fecha_inicio_depreciacion' => '2021-04-01', 'descripcion' => 'Almacén de productos e insumos'],
            ['codigo' => 'IN-003', 'nombre' => 'Oficina Vedado (piso 5)', 'tipo' => 'inmueble', 'costo_original' => 1200000.00, 'valor_residual' => 120000.00, 'vida_util_anos' => 25, 'fecha_adquisicion' => '2022-01-10', 'fecha_inicio_depreciacion' => '2022-02-01', 'descripcion' => 'Oficina administrativa y dirección'],
            ['codigo' => 'IN-004', 'nombre' => 'Plaza de Estacionamiento', 'tipo' => 'inmueble', 'costo_original' => 350000.00, 'valor_residual' => 35000.00, 'vida_util_anos' => 20, 'fecha_adquisicion' => '2023-05-01', 'fecha_inicio_depreciacion' => '2023-06-01'],

            // Otros (6)
            ['codigo' => 'OT-001', 'nombre' => 'Aire Acondicionado Split 24K BTU', 'tipo' => 'otro', 'costo_original' => 18500.00, 'valor_residual' => 500.00, 'vida_util_anos' => 8, 'fecha_adquisicion' => '2024-04-10', 'fecha_inicio_depreciacion' => '2024-05-01'],
            ['codigo' => 'OT-002', 'nombre' => 'Planta Eléctrica Cummins 50KVA', 'tipo' => 'otro', 'costo_original' => 185000.00, 'valor_residual' => 15000.00, 'vida_util_anos' => 15, 'fecha_adquisicion' => '2023-08-20', 'fecha_inicio_depreciacion' => '2023-09-01', 'descripcion' => 'Generador de emergencia para oficina central'],
            ['codigo' => 'OT-003', 'nombre' => 'Cámara de Seguridad Hikvision 8 canales', 'tipo' => 'otro', 'costo_original' => 28000.00, 'valor_residual' => 1000.00, 'vida_util_anos' => 5, 'fecha_adquisicion' => '2024-01-05', 'fecha_inicio_depreciacion' => '2024-02-01'],
            ['codigo' => 'OT-004', 'nombre' => 'Sistema de Alarma DSC', 'tipo' => 'otro', 'costo_original' => 12500.00, 'valor_residual' => 500.00, 'vida_util_anos' => 6, 'fecha_adquisicion' => '2024-02-10', 'fecha_inicio_depreciacion' => '2024-03-01'],
            ['codigo' => 'OT-005', 'nombre' => 'Caja Fuerte Chubbsk 200kg', 'tipo' => 'otro', 'costo_original' => 22000.00, 'valor_residual' => 2000.00, 'vida_util_anos' => 20, 'fecha_adquisicion' => '2023-12-01', 'fecha_inicio_depreciacion' => '2024-01-01', 'descripcion' => 'Caja fuerte para documentos y valores'],
            ['codigo' => 'OT-006', 'nombre' => 'Sistema de Riego Automatizado', 'tipo' => 'otro', 'costo_original' => 9500.00, 'valor_residual' => 300.00, 'vida_util_anos' => 5, 'fecha_adquisicion' => '2024-06-15', 'fecha_inicio_depreciacion' => '2024-07-01'],
        ];

        foreach ($activos as $data) {
            $data['empresa_id'] = $empresaId;
            $data['esta_activo'] = true;
            $data['observaciones'] = 'Activo registrado durante la configuración inicial del sistema.';

            ActivoFijo::create($data);
        }
    }
}
