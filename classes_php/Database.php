<?php

include_once("Config.php");
include_once("DataRequest.php");
include_once("DataResponse.php");

class Database extends SQLite3 {

    public function __construct() {
        $this->open(Config::DBFile);
    }
    
    public function Exec($dataRequest) {

        $dataResponse = new DataResponse();

        if ($dataRequest->isValid()) {
            $sql = "SELECT * FROM '" . Config::tabName . "'";
            if (isset($dataRequest->firstDate)) {
                $sql .= " WHERE timestamp >= '" . $dataRequest->firstDate ."'";
                if (isset($dataRequest->lastDate)) {
                    $sql .= " AND timestamp <= '" . $dataRequest->lastDate . "'";
                }
            }
            else if (isset($dataRequest->lastDate)) {
                $sql .= " WHERE timestamp <= '" . $dataRequest->lastDate . "'";
            }
            $sql .= ";";

            $ret = $this->query($sql);
            
            while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                array_push($dataResponse->labels, $row['timestamp']);
                array_push($dataResponse->temp_data, $row['temp']);
                array_push($dataResponse->pres_data, $row['pres']);
                array_push($dataResponse->hum_data, $row['hum']);
            }

            $dataResponse->setValid();
        }
        else {
            $dataResponse->msg = "Invalid DataRequest!";
            $dataResponse->setInvalid();
        }

        return $dataResponse;
    }
}

?>