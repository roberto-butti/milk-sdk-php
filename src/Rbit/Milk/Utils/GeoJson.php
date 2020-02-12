<?php

namespace Rbit\Milk\Utils;

class GeoJson
{
    private array $featureCollection = [];


    public function addPoint($latitude, $longitude, $properties = null, $id = null) {
        $point = new \GeoJson\Geometry\Point([$latitude, $longitude]);
        $f = new \GeoJson\Feature\Feature($point, $properties, $id);
        $this->featureCollection[] = $f;
    }

    public function getString() {
        $fs = new \GeoJson\Feature\FeatureCollection($this->featureCollection);
        return json_encode($fs);
    }

}