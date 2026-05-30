<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\Transaccion;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $users = collect([User::create([
                'name' => 'Admin Mipyme',
                'email' => 'admin@mipyme.com',
                'password' => bcrypt('password'),
            ])]);
        }

        $nombresEmpresa = [
            'Comercializadora del Centro SRL',
            'Distribuidora Oriental',
            'Servicios Técnicos del Oeste',
        ];
        $nits = ['CU-2024-000123', 'CU-2024-000456', 'CU-2024-000789'];
        $actividades = [
            'Comercio minorista de productos generales',
            'Distribución de alimentos y bebidas',
            'Servicios técnicos y consultoría',
        ];

        foreach ($users as $i => $user) {
            $empresa = $user->empresa;

            if (!$empresa) {
                $idx = $i % count($nombresEmpresa);
                $empresa = Empresa::create([
                    'user_id' => $user->id,
                    'nombre' => $nombresEmpresa[$idx],
                    'nit' => $nits[$idx],
                    'actividad_economica' => $actividades[$idx],
                    'tipo_contabilidad' => 'simplificada',
                ]);
            }

            $this->crearTransaccionesPara($empresa);
        }
    }

    private function crearTransaccionesPara(Empresa $empresa): void
    {
        if ($empresa->transacciones()->count() > 10) {
            $this->command->info("Empresa '{$empresa->nombre}' ya tiene transacciones, saltando...");
            return;
        }

        $ingresoCategories = [];
        $ingresoData = [
            ['nombre' => 'Ventas de productos', 'color' => '#22c55e', 'icono' => 'shopping-cart'],
            ['nombre' => 'Servicios prestados', 'color' => '#3b82f6', 'icono' => 'briefcase'],
            ['nombre' => 'Comisiones recibidas', 'color' => '#8b5cf6', 'icono' => 'percent'],
            ['nombre' => 'Ingresos financieros', 'color' => '#06b6d4', 'icono' => 'trending-up'],
            ['nombre' => 'Otros ingresos', 'color' => '#eab308', 'icono' => 'plus-circle'],
        ];
        foreach ($ingresoData as $cat) {
            $ingresoCategories[] = Categoria::firstOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => $cat['nombre'], 'tipo' => 'ingreso'],
                ['color' => $cat['color'], 'icono' => $cat['icono'], 'es_activo' => true]
            );
        }

        $gastoCategories = [];
        $gastoData = [
            ['nombre' => 'Compra de mercancías', 'color' => '#ef4444', 'icono' => 'package'],
            ['nombre' => 'Servicios públicos', 'color' => '#f97316', 'icono' => 'zap'],
            ['nombre' => 'Alquiler', 'color' => '#6366f1', 'icono' => 'home'],
            ['nombre' => 'Salarios', 'color' => '#ec4899', 'icono' => 'users'],
            ['nombre' => 'Transporte y logística', 'color' => '#14b8a6', 'icono' => 'truck'],
            ['nombre' => 'Publicidad y marketing', 'color' => '#a855f7', 'icono' => 'megaphone'],
            ['nombre' => 'Gastos de oficina', 'color' => '#6b7280', 'icono' => 'printer'],
            ['nombre' => 'Impuestos y licencias', 'color' => '#dc2626', 'icono' => 'file-text'],
        ];
        foreach ($gastoData as $cat) {
            $gastoCategories[] = Categoria::firstOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => $cat['nombre'], 'tipo' => 'gasto'],
                ['color' => $cat['color'], 'icono' => $cat['icono'], 'es_activo' => true]
            );
        }

        $metodosPago = ['efectivo', 'transferencia', 'electronico'];
        $estados = ['confirmado', 'confirmado', 'confirmado', 'pendiente', 'anulado'];

        $descripcionesIngreso = [
            'Venta de mercancía al por mayor',
            'Venta de productos al por menor',
            'Servicio de consultoría',
            'Honorarios profesionales',
            'Comisión por venta realizada',
            'Intereses ganados en cuenta bancaria',
            'Venta de productos de temporada',
            'Servicio de mantenimiento técnico',
            'Asesoría contable y fiscal',
            'Venta de inventario excedente',
            'Comisión por intermediación',
            'Servicio de diseño gráfico',
            'Venta de equipos usados',
            'Rendimiento de inversiones',
            'Servicio de capacitación',
        ];

        $descripcionesGasto = [
            'Compra de inventario para reventa',
            'Pago de factura eléctrica',
            'Pago de agua',
            'Pago de servicios de telecomunicaciones',
            'Pago de alquiler mensual del local',
            'Nómina semanal de empleados',
            'Pago de salario mensual',
            'Flete y transporte de mercancías',
            'Campaña publicitaria en redes sociales',
            'Compra de materiales de oficina',
            'Pago de impuesto municipal',
            'Mantenimiento de equipos',
            'Compra de combustible',
            'Pago de servicio de internet',
            'Gastos de envío y mensajería',
            'Suscripción a software contable',
            'Gastos de representación',
            'Seguro del local comercial',
            'Pago de licencia de funcionamiento',
            'Compra de uniformes laborales',
        ];

        $now = now();
        $currentYear = $now->year;
        $previousYear = $currentYear - 1;

        $transacciones = [];

        for ($year = $previousYear; $year <= $currentYear; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $daysInMonth = now()->setDate($year, $month, 1)->daysInMonth;

                if ($year === $currentYear && $month > $now->month) {
                    break;
                }

                $numIngresos = rand(3, 6);
                $numGastos = rand(4, 7);

                for ($i = 0; $i < $numIngresos; $i++) {
                    $day = rand(1, min($daysInMonth, 28));
                    $transacciones[] = [
                        'empresa_id' => $empresa->id,
                        'categoria_id' => $ingresoCategories[array_rand($ingresoCategories)]->id,
                        'tipo' => 'ingreso',
                        'monto' => round(rand(500, 50000) + rand(0, 99) / 100, 2),
                        'descripcion' => $descripcionesIngreso[array_rand($descripcionesIngreso)],
                        'fecha' => sprintf('%d-%02d-%02d', $year, $month, $day),
                        'metodo_pago' => $metodosPago[array_rand($metodosPago)],
                        'estado' => $estados[array_rand($estados)],
                        'created_at' => sprintf('%d-%02d-%02d 10:00:00', $year, $month, $day),
                        'updated_at' => sprintf('%d-%02d-%02d 10:00:00', $year, $month, $day),
                    ];
                }

                for ($i = 0; $i < $numGastos; $i++) {
                    $day = rand(1, min($daysInMonth, 28));
                    $transacciones[] = [
                        'empresa_id' => $empresa->id,
                        'categoria_id' => $gastoCategories[array_rand($gastoCategories)]->id,
                        'tipo' => 'gasto',
                        'monto' => round(rand(200, 20000) + rand(0, 99) / 100, 2),
                        'descripcion' => $descripcionesGasto[array_rand($descripcionesGasto)],
                        'fecha' => sprintf('%d-%02d-%02d', $year, $month, $day),
                        'metodo_pago' => $metodosPago[array_rand($metodosPago)],
                        'estado' => $estados[array_rand($estados)],
                        'created_at' => sprintf('%d-%02d-%02d 10:00:00', $year, $month, $day),
                        'updated_at' => sprintf('%d-%02d-%02d 10:00:00', $year, $month, $day),
                    ];
                }
            }
        }

        $chunks = array_chunk($transacciones, 50);
        foreach ($chunks as $chunk) {
            Transaccion::insert($chunk);
        }

        $total = count($transacciones);
        $this->command->info("Se crearon {$total} transacciones de ejemplo.");
        $this->command->info("Empresa: {$empresa->nombre}");
        $this->command->info("Categorías: " . (count($ingresoCategories) + count($gastoCategories)) . " (" . count($ingresoCategories) . " ingresos, " . count($gastoCategories) . " gastos)");
    }
}
