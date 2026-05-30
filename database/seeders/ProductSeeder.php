<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Kardex;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
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

            if ($empresa->productos()->count() > 5) {
                $this->command->info("Empresa '{$empresa->nombre}' ya tiene productos, saltando...");
                continue;
            }

            $this->crearProductosPara($empresa);
        }
    }

    private function crearProductosPara(Empresa $empresa): void
    {
        $products = [
            ['PR-001', 'Arroz premium 1kg', 'Arroz de grano largo, bolsa 1kg', 'kg', 25.00, 42.00, 200, 50, 'Alimentos'],
            ['PR-002', 'Frijoles negros 1kg', 'Frijoles negros seleccionados, bolsa 1kg', 'kg', 30.00, 50.00, 150, 40, 'Alimentos'],
            ['PR-003', 'Aceite vegetal 1L', 'Aceite vegetal refinado, botella 1L', 'l', 45.00, 75.00, 120, 30, 'Alimentos'],
            ['PR-004', 'Azúcar blanca 1kg', 'Azúcar blanca refinada, bolsa 1kg', 'kg', 18.00, 32.00, 300, 60, 'Alimentos'],
            ['PR-005', 'Café molido 250g', 'Café molido premium, paquete 250g', 'und', 55.00, 95.00, 80, 20, 'Alimentos'],
            ['PR-006', 'Leche en polvo 400g', 'Leche en polvo entera, lata 400g', 'und', 80.00, 135.00, 60, 15, 'Alimentos'],
            ['PR-007', 'Galletas surtidas 200g', 'Paquete de galletas surtidas 200g', 'und', 12.00, 22.00, 250, 50, 'Alimentos'],
            ['PR-008', 'Agua embotellada 1.5L', 'Agua purificada, botella 1.5L', 'l', 8.00, 15.00, 500, 100, 'Alimentos'],
            ['PR-009', 'Detergente en polvo 500g', 'Detergente en polvo multiusos 500g', 'und', 22.00, 38.00, 180, 40, 'Limpieza'],
            ['PR-010', 'Jabón de baño 125g', 'Jabón de baño humectante 125g', 'und', 10.00, 18.00, 400, 80, 'Limpieza'],
            ['PR-011', 'Cloro 1L', 'Cloro líquido concentrado 1L', 'l', 12.00, 22.00, 160, 40, 'Limpieza'],
            ['PR-012', 'Desinfectante multiusos 750ml', 'Desinfectante multiusos aroma limón 750ml', 'und', 28.00, 48.00, 90, 25, 'Limpieza'],
            ['PR-013', 'Papel higiénico pack 12', 'Paquete de 12 rollos de papel higiénico', 'und', 65.00, 110.00, 70, 20, 'Limpieza'],
            ['PR-014', 'Escoba cerdas duras', 'Escoba de cerdas duras con mango', 'und', 35.00, 60.00, 45, 15, 'Limpieza'],
            ['PR-015', 'Bolígrafo azul caja 50', 'Caja con 50 bolígrafos azules', 'und', 40.00, 70.00, 30, 10, 'Oficina'],
            ['PR-016', 'Papel bond carta resma', 'Resma de papel bond tamaño carta 500 hojas', 'und', 85.00, 145.00, 50, 15, 'Oficina'],
            ['PR-017', 'Cuaderno profesional 100 hojas', 'Cuaderno profesional rayado 100 hojas', 'und', 25.00, 45.00, 100, 30, 'Oficina'],
            ['PR-018', 'Carpeta archivadora', 'Carpeta archivadora tamaño oficio', 'und', 18.00, 32.00, 120, 30, 'Oficina'],
            ['PR-019', 'Tóner impresora HP', 'Cartucho de tóner para impresora HP', 'und', 350.00, 580.00, 10, 5, 'Oficina'],
            ['PR-020', 'Cable USB-C 1m', 'Cable USB-C a USB-A 1 metro', 'und', 30.00, 55.00, 80, 20, 'Electrónica'],
            ['PR-021', 'Cargador pared USB', 'Cargador de pared doble puerto USB 2.1A', 'und', 55.00, 95.00, 40, 10, 'Electrónica'],
            ['PR-022', 'Audífonos diadema', 'Audífonos diadema con micrófono', 'und', 120.00, 210.00, 25, 8, 'Electrónica'],
            ['PR-023', 'Mouse óptico USB', 'Mouse óptico con cable USB 1600 DPI', 'und', 65.00, 115.00, 35, 10, 'Electrónica'],
            ['PR-024', 'Teclado USB estándar', 'Teclado USB estándar en español', 'und', 90.00, 155.00, 20, 8, 'Electrónica'],
            ['PR-025', 'Camisa manga corta', 'Camisa manga corta algodón, varios colores', 'und', 95.00, 165.00, 60, 15, 'Textiles'],
            ['PR-026', 'Pantalón casual', 'Pantalón casual tela mixta, tallas 30-42', 'und', 160.00, 280.00, 40, 12, 'Textiles'],
            ['PR-027', 'Toalla de baño', 'Toalla de baño algodón 70x140cm', 'und', 55.00, 95.00, 80, 20, 'Textiles'],
            ['PR-028', 'Sábana cama matrimonial', 'Juego de sábana cama matrimonial 2 piezas', 'und', 130.00, 225.00, 30, 10, 'Textiles'],
            ['PR-029', 'Martillo de carpintero', 'Martillo de carpintero 500g con mango', 'und', 75.00, 130.00, 25, 8, 'Ferretería'],
            ['PR-030', 'Destornillador juego 6pz', 'Juego de 6 destornilladores planos y estrella', 'und', 85.00, 145.00, 30, 10, 'Ferretería'],
            ['PR-031', 'Cinta métrica 5m', 'Cinta métrica metálica 5 metros', 'und', 25.00, 45.00, 50, 12, 'Ferretería'],
            ['PR-032', 'Candado seguridad', 'Candado de seguridad con llave 40mm', 'und', 35.00, 60.00, 70, 15, 'Ferretería'],
            ['PR-033', 'Bombillo LED 12W', 'Bombillo LED 12W luz blanca', 'und', 20.00, 38.00, 150, 40, 'Ferretería'],
            ['PR-034', 'Cinta adhesiva pack 6', 'Pack 6 cintas adhesivas transparentes 50m', 'und', 28.00, 50.00, 60, 15, 'Ferretería'],
        ];

        $now = now();
        $count = 0;

        foreach ($products as [$codigo, $nombre, $descripcion, $unidad, $costo, $venta, $stock, $minimo, $categoria]) {
            $producto = Producto::firstOrCreate(
                ['empresa_id' => $empresa->id, 'codigo' => $codigo],
                [
                    'categoria_producto' => $categoria,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'unidad_medida' => $unidad,
                    'precio_costo' => $costo,
                    'precio_venta' => $venta,
                    'existencias' => $stock,
                    'stock_minimo' => $minimo,
                    'es_servicio' => false,
                    'esta_activo' => true,
                ]
            );

            if ($producto->wasRecentlyCreated) {
                Kardex::create([
                    'producto_id' => $producto->id,
                    'tipo_movimiento' => 'entrada',
                    'tipo_origen' => 'compra',
                    'cantidad' => $stock,
                    'precio_unitario' => $costo,
                    'costo_total' => $stock * $costo,
                    'existencias_anterior' => 0,
                    'existencias_nueva' => $stock,
                    'fecha' => $now->startOfYear()->toDateString(),
                    'referencia' => 'INV-INICIAL-' . $codigo,
                    'notas' => 'Inventario inicial del año',
                ]);
                $count++;
            }
        }

        $this->command->info("Se crearon {$count} productos con kardex para '{$empresa->nombre}'.");
    }
}
