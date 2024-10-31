<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registro;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GraficosController extends Controller
{
    public function obtenerDatos(Request $request)
    {
        try {
            // Obtener el parámetro de fecha desde la solicitud
            $fecha = $request->input('fecha');

            // Convertir la fecha a un objeto Carbon
            $fecha = Carbon::parse($fecha);

            // Obtener los datos de la tabla 'registro' para la fecha seleccionada
            $registros = Registro::selectRaw('HOUR(hora_entrada) as hora, tipo, COUNT(*) as cantidad')
                ->whereDate('fecha', $fecha)
                ->groupBy('hora', 'tipo')
                ->get();

            // Verifica que se están obteniendo los datos correctamente
            if ($registros->isEmpty()) {
                Log::info("No se encontraron registros en la tabla para la fecha: " . $fecha->toDateString());
                return response()->json(['message' => 'No hay datos disponibles para mostrar en el gráfico.'], 200);
            } else {
                Log::info("Registros obtenidos para la fecha " . $fecha->toDateString() . ": ", $registros->toArray());
            }

            // Formatear los datos para Google Charts
            $data = [['Hora', 'Carros', 'Bicicletas', 'Scooter Electrico', 'Motos']];
            $horas = range(0, 23);
            $tipos = ['carro', 'bicicleta', 'scooter electrico', 'moto'];

            foreach ($horas as $hora) {
                // Inicializar cada fila con ceros para cada tipo de vehículo
                $fila = [sprintf('%02d:00', $hora), 0, 0, 0, 0];
                foreach ($tipos as $index => $tipo) {
                    // Filtrar registros solo para la hora actual y tipo actual
                    $registrosHora = $registros->where('hora', $hora);

                    // Comprobar que $registrosHora no esté vacío
                    if ($registrosHora->isNotEmpty()) {
                        $registro = $registrosHora->firstWhere('tipo', $tipo);
                        if ($registro) {
                            $fila[$index + 1] = $registro->cantidad;
                        }
                    }
                }
                $data[] = $fila;
            }

            Log::info("Datos formateados para el gráfico: ", $data);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error en obtenerDatos: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los datos'], 500);
        }
    }
}