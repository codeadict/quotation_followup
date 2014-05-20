<?php
/**
 * Created by PhpStorm.
 * Developer: Dairon Medina <info@gydsystems.com>
 * Date: 5/13/14
 * Time: 7:35 PM
 */

define('BASE_URL', dirname(__DIR__));
include("openerplib.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Quotation Unsubscribe</title>
    <link rel='stylesheet' href='style.css' >
</head>
<body bgcolor="#FFFFFF">

<?php
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

    $qt = $open->sale_order->get($_POST['quoteid']);
    $qt->send_follow_mails = false;
    $qt->client_unsuscribed = true;
    $qt->state = 'cancel';
    $qt->note = $comment;
    $qt->save();
    if (!is_null($open->getError())){
        echo $open->getError();
    } else {
        echo '<div class="success">Unsuscribed Successfully from quotation reminder. Thanks.</div>';
    }
} else {
    echo 'Please fill all the data.';
}

exit;

?>

</body>
</html>