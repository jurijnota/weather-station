<?php

class DataRequest {
    private $valid = false;
    public $firstDate = NULL;
    public $lastDate = NULL;
    
    public function __construct($request) {
        if (isset($request->firstDate)) {
            $this->firstDate = $request->firstDate;
        }
        if (isset($request->lastDate)) {
            $this->lastDate = $request->lastDate;
        }
        if (isset($request->valid)) {
            $this->valid = true;
        }
        else {
            $this->valid = false;
        }
    }

    public function isValid() {
        return $this->valid;
    }

    public function setValid() {
        $this->valid = true;
    }

    public function setInvalid() {
        $this->valid = false;
    }
}

?>