<?php

/**
 * Created by PhpStorm.
 * Developer: Dairon Medina <info@gydsystems.com>
 * Date: 5/13/14
 * Time: 7:35 PM
 */

define('BASE_URL', dirname(__DIR__));
include("xmlrpc.inc");

class OpenERPConn
{

    public function is_unsuscribed($company_id, $quote_id)
    {

    }

    public function unsubscribe($usr, $password, $database, $server, $post)
    {
        #Data accessvariables for openerp
        $user = $usr;
        $pass = $password;
        $db = $database;
        $server_url = $server; //'http://localhost:8069/xmlrpc/'

        #try to authentiate using xmlrpc method
        $client = new xmlrpc_client($server_url . 'common');

        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($db, "string"));
        $msg->addParam(new xmlrpcval($user, "string"));
        $msg->addParam(new xmlrpcval($pass, "string"));


        $res =  & $client->send($msg);

        //Check if not error
        if (!$res->faultCode()) {

            $val = $res->value();
            $id = $val->scalarval();

            if (empty($id)) {
                echo "Connection error = ";
            } #If not empty userid proceed to unsuscribe action
            else {
                $client2 = new xmlrpc_client($server_url . 'object');

                $values = array(
                    "reason" => new xmlrpcval($post['reason'], "string"),
                    "comments" => new xmlrpcval($post['comments'], "string")
                );

                $msg = new xmlrpcmsg('execute');
                $msg->addParam(new xmlrpcval($db, "string"));
                $msg->addParam(new xmlrpcval($id, "int"));
                $msg->addParam(new xmlrpcval($pass, "string"));
                $msg->addParam(new xmlrpcval("sale.order", "string"));
                $msg->addParam(new xmlrpcval("write", "string"));
                $msg->addParam(new xmlrpcval($post['quoteid'], "array"));
                $msg->addParam(new xmlrpcval($values, "struct"));

                $res2 = &$client2->send($msg);

            }
        } else {
            echo 'Error: '.$res->faultString();
        }

    }
}

//HANDLE THE FORM SUBMIT
if (isset($_POST['reason']) && $_POST['reason'] != '') {
    $arrData = array();
    $arrData = array_merge($arrData, (array)$_POST);

    $cnt = new OpenERPConn();

    /* This function unsuscribes a user from quotation and fill some aditional data */
    //Change to fit your configuration
    $cnt->unsubscribe('admin', 'admin', 'ecuaerp', 'http://localhost:8069/xmlrpc/', $arrData);
} else {
    echo 'Please fill all the data.';
}

exit;

?>