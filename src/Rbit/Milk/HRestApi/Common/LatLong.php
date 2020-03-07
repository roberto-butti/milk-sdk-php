<?php



namespace Rbit\Milk\HRestApi\Common;


class LatLong {
    private float $latitude;
    private float $longitude;


    public function __construct(float $latitude, float $longitude)
    {
        $this->setLatLong($latitude, $longitude);

    }
    public function getLatitude(): float {
        return $this->latitude;
    }
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLatLong(float $latitude, float $longitude): void
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }



    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getString():string
    {
        return $this->latitude.",".$this->longitude;
    }




}
