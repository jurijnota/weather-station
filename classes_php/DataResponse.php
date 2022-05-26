<?php

class DataResponse {
    public $valid = false;
    public $labels = array();
    public $temp_data = array();
    public $pres_data = array();
    public $hum_data = array();
    public $msg = "";

    public function isValid(){
        return $valid;
    }

    public function setValid() {
        $this->valid = true;
    }

    public function setInvalid() {
        $this->valid = false;
    }
}

?>