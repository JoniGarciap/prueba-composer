<?php
include 'client.php';

class ApiClientes{

    function getAll(){
        $clientes = new client();
        $arrClientes = array();
        $arrClientes["register"] = array();

        if(isset($_GET["date"])){
            $res = $clientes->getClients(" WHERE date BETWEEN '".$_GET["date"]."' AND CURRENT_DATE");
        }
        else if(isset($_GET["domain"])){
            $res = $clientes->getClients(" WHERE clientEmail like '%".$_GET["domain"]."'");
        }
        else $res = $clientes->getClients("");

        if($res->rowCount()){
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $register = array(
                    'clientID' => $row['clientID'],
                    'clientEmail' => $row['clientEmail'],
                    'date' => $row['date'],
                    'orderQty' => $row['orderQty'],
                );
                array_push($arrClientes["register"],$register);
            }
            http_response_code(200);
            echo json_encode($arrClientes);
        }
        else{
            http_response_code(404);
            echo json_encode(array('message' => 'Element not found'));
        }
    }

    
}

$api = new ApiClientes();

$api->getAll();
?>