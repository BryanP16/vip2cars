<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Vehiculo;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'Nombre' => 'Bryan Polo',
            'Correo' => 'bpolo@gmail.com',
        ]);




        $clients = [
            [
                'Nombres' => 'Bryan', 'Apellidos' => 'Polo Gomez',
                'TipoDocumento' => 'DNI', 'NroDocumento' => '70401296',
                'Correo' => 'bryanj199923@gmail.com', 'Telefono' => '+51 912 934 805',
            ],
            [
                'Nombres' => 'Ana', 'Apellidos' => 'Torres Vega',
                'TipoDocumento' => 'DNI', 'NroDocumento' => '87654321',
                'Correo' => 'ana.torres@gmail.com', 'Telefono' => '+51 976 543 210',
            ],
            [
                'Nombres' => 'Luis', 'Apellidos' => 'García Paredes',
                'TipoDocumento' => 'CE', 'NroDocumento' => 'CE-001234',
                'Correo' => 'luis.garcia@gmail.com', 'Telefono' => '+51 965 432 109',
            ],
            [
                'Nombres' => 'María', 'Apellidos' => 'López Castillo',
                'TipoDocumento' => 'DNI', 'NroDocumento' => '11223344',
                'Correo' => 'maria.lopez@gmail.com', 'Telefono' => '+51 954 321 098',
            ],
            [
                'Nombres' => 'Pedro', 'Apellidos' => 'Ruiz Flores',
                'TipoDocumento' => 'RUC', 'NroDocumento' => '20123456789',
                'Correo' => 'pedro.ruiz@empresa.com', 'Telefono' => '+51 943 210 987',
            ],
        ];

        $vehicles = [
            ['placa' => 'ABC-123', 'marca' => 'Toyota',        'modelo' => 'Corolla',   'anio_fabricacion' => 2020, 'color' => 'Blanco'],
            ['placa' => 'XYZ-456', 'marca' => 'Toyota',        'modelo' => 'Hilux',     'anio_fabricacion' => 2022, 'color' => 'Negro'],
            ['placa' => 'DEF-789', 'marca' => 'Hyundai',       'modelo' => 'Tucson',    'anio_fabricacion' => 2021, 'color' => 'Gris'],
            ['placa' => 'GHI-012', 'marca' => 'Ford',          'modelo' => 'Explorer',  'anio_fabricacion' => 2019, 'color' => 'Azul'],
            ['placa' => 'JKL-345', 'marca' => 'BMW',           'modelo' => '320i',      'anio_fabricacion' => 2023, 'color' => 'Blanco'],
            ['placa' => 'MNO-678', 'marca' => 'Mercedes-Benz', 'modelo' => 'GLE 350',   'anio_fabricacion' => 2022, 'color' => 'Negro'],
        ];

        foreach ($clients as $i => $clientData) {
            $client = Cliente::firstOrCreate(
                ['TipoDocumento' => $clientData['TipoDocumento'], 'NroDocumento' => $clientData['NroDocumento']],
                $clientData
            );

            if (isset($vehicles[$i])) {
                $client->vehiculos()->firstOrCreate(
                    ['placa' => $vehicles[$i]['placa']],
                    $vehicles[$i]
                );
            }
        }

        // Client 1 has two vehicles
        $firstClient = Cliente::where('NroDocumento', '12345678')->first();
        if ($firstClient && isset($vehicles[1])) {
            $firstClient->vehiculos()->firstOrCreate(
                ['placa' => $vehicles[1]['placa']],
                $vehicles[1]
            );
        }
    }
}
