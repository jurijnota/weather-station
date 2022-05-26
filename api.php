<?php
// Enable buffering
ob_start();

include_once("classes_php/Database.php");
include_once("classes_php/DataRequest.php");

header('Content-Type: application/json');

class Api {

    public static function HandleDataRequest($request) {
        
        $dataRequest = new DataRequest($request);
        $db = new Database();
        if(!$db) {
            $dataResponse = new DataResponse();
            $dataResponse->msg = $db->lastErrorMsg();
            $dataResponse->setInvalid();
        }
        else {
            $dataResponse = $db->Exec($dataRequest);
        }
        $db->close();
        return $dataResponse;
    }

    public static function Main() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $input = file_get_contents('php://input');
            $request = json_decode($input);
            $response = self::HandleDataRequest($request);
            echo json_encode($response);
        }
        else {
            echo "GET METHOD NOT SUPPORTED";
        }
    }
}

Api::Main();
?>
