<?php

namespace App\Utilities;

class DistanceUtil
{
    /**
     * Get distance km between to points
     * @param $latitude1
     * @param $longitude1
     * @param $latitude2
     * @param $longitude2
     * @return float
     */
    public function getDistance($latitude1, $longitude1, $latitude2 = 30.04954153622445, $longitude2 = 31.240285142328773): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $lat1 = deg2rad($latitude1);
        $lat2 = deg2rad($latitude2);

        $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
