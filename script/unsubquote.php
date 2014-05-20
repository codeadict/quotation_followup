<?php

/**
 * Created by PhpStorm.
 * Developer: Dairon Medina <info@gydsystems.com>
 * Date: 5/13/14
 * Time: 7:35 PM
 */

define('BASE_URL', dirname(__DIR__));
include("openerplib.php");

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

    $qt = $open->res_partner('name', 'state', 'note', 'send_follow_mails', 'client_unsuscribed')->get($_POST['quoteid']);
    $qt->send_follow_mails = false;
    $qt->client_unsuscribed = true;
    $qt->state = 'cancel';
    $qt->note = $comment;
    $qt->save();
    if (!is_null($open->getError())){
        echo $open->getError();
    } else {
        echo 'Unsuscribed Successfully from quotation reminder. Thanks.';
    }
} else {
    echo 'Please fill all the data.';
}

exit;

?>