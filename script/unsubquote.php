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
            else
            {
                $client2 = new xmlrpc_client($server_url . 'common');

                if ($post['reason'] == 'toexpensive'){
                    $comm = 'Client said is too expensive.<br>';
                } else {
                    $comm = 'Client purchased from other company.<br>';
                }
                $comment = $comm . $post['comments'];
                
                $values = array();
                $values['note'] = new xmlrpcval($comment, "string");
                //TODO: Put if to cancel or not the quote
                $values['state'] = new xmlrpcval('cancel', "string");


                $msg = new xmlrpcmsg('execute');
                $msg->addParam(new xmlrpcval($db, "string"));
                $msg->addParam(new xmlrpcval($id, "int"));
                $msg->addParam(new xmlrpcval($pass, "string"));
                $msg->addParam(new xmlrpcval("sale.order", "string"));
                $msg->addParam(new xmlrpcval("write", "string"));
                $msg->addParam(new xmlrpcval($post['quoteid'], "array"));
                $msg->addParam(new xmlrpcval($values, "struct"));

                $res2 = $client2->send($msg);

                if (!$res2->faultCode()){
                    echo 'You are now unsuscribed from quotation '.$res2->value()->scalarval().' . Thanks for your interest.';
                } else {
                    echo 'Error: '.$res2->faultString();
                }

            }
        } else {
            echo 'Error: '.$res->faultString();
        }

    }
}

//HANDLE THE FORM SUBMIT
if (isset($_POST['reason']) && $_POST['reason'] != '') {
    /* This function unsuscribes a user from quotation and fill some aditional data */
    //Change to fit your configuration

    if ($_POST['reason'] == 'toexpensive'){
        $comm = 'Client said is too expensive.';
    } else {
        $comm = 'Client purchased from other company.';
    }
    $comment = $comm . $_POST['comments'];

    $open = new OpenERP();

    $qt = $open->res_partner('name', 'state', 'note')->get($_POST['quoteid']);
    $qt->state = 'cancel';
    $qt->note = $comment;
    $qt->save();
} else {
    echo 'Please fill all the data.';
}

exit;

?>