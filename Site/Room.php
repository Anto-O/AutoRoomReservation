<?php 

class Room 
{
    private $id;
    public function getId() {
         return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    private $number;
    public function getNumber() {
            return $this->number;
    }
    public function setNumber($number) {
        $this->number = $number;
    }

    private $area;
    public function getArea() {
            return $this->area;
    }
    public function setArea($area) {
        $this->area = $area;
    }

    private $price;
    public function getPrice() {
            return $this->price;
    }
    public function setPrice($price) {
        $this->price = $price;
    }

    private $place;
    public function getPlace() {
        return $this->place;
    }
    public function setPlace($place) {
        $this->place = $place;
    }

    private $apartId;
    public function getApartId() {
        return $this->apartId;
    }
    public function setApartId($apartId) {
        $this->apartId = $apartId;
    }



    public function toJSON(){
        // Retourne l'objet au format JSON
        return json_encode(get_object_vars($this));
    }
}

?>