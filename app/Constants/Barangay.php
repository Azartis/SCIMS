<?php

namespace App\Constants;

class Barangay
{
    /**
     * Approximate map coordinates for Dulag, Leyte barangays (center ~10.95°N, 125°E).
     * Uses a grid pattern; replace with actual coordinates if you have GeoJSON or survey data.
     */
    public static function mapCoordinates(): array
    {
        $list = self::list();
        $centerLat = 10.9525;
        $centerLng = 124.9919;
        $latStep = 0.022;
        $lngStep = 0.028;
        $cols = 8;
        $coords = [];
        foreach ($list as $i => $name) {
            $row = (int) floor($i / $cols);
            $col = $i % $cols;
            $coords[$name] = [
                $centerLat + ($row - 2.5) * $latStep,
                $centerLng + ($col - 3.5) * $lngStep,
            ];
        }
        return $coords;
    }

    public static function list()
    {
        return [
            'Alegre',
            'Arado',
            'Bulod',
            'Batug',
            'Bolongtohan',
            'Cabacungan',
            'Cabarasan',
            'Cabatoan',
            'Calipayan',
            'Calubian',
            'Camitoc',
            'Camote',
            'Dacay',
            'Del Carmen',
            'Del Pilar',
            'Fatima',
            'General Roxas',
            'Luan',
            'Magsaysay',
            'Maricum',
            'Barbo (Poblacion Sawang)',
            'Buntay (Poblacion Sawang)',
            'Cambula (Poblacion Sawang)',
            'Candao (Poblacion Sawang)',
            'Catmonan (Poblacion Sawang)',
            'Combis (Poblacion Sawang)',
            'Highway (Poblacion Sawang)',
            'Market Site (Poblacion Sawang)',
            'San Miguel (Poblacion Sawang)',
            'Serrano (Poblacion Sawang)',
            'Sungi (Poblacion Sawang)',
            'Rawis',
            'Rizal',
            'Romualdes',
            'Sabang Daguitan',
            'Salvacion',
            'San Agustin',
            'San Antonio',
            'San Isidro',
            'San Jose',
            'San Rafael',
            'San Vicente',
            'Tabu',
            'Tigbao',
            'Victory',
        ];
    }
}
