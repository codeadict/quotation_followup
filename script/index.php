<?php
/**
 * Created by PhpStorm.
 * Developer: Dairon Medina <info@gydsystems.com>
 * Date: 5/13/14
 * Time: 7:35 PM
 */

if (!empty($_GET['qid'])){
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

<!-- HEADER -->
<table class="head-wrap" bgcolor="#B20305">
    <tbody>
    <tr>
        <td></td>
        <td class="header container">

            <div class="content">
                <table bgcolor="#B20305">
                    <tbody>
                    <tr>
                        <td><img src="http://www.brash-scales.com/images/logo.png"></td>
                        <td align="right"><h6 class="collapse">Unsuscribe from Quotation Reminder</h6></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </td>
        <td></td>
    </tr>
    </tbody>
</table>
<!-- /HEADER -->

<!-- BODY -->
<table class="body-wrap">
    <tbody>
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <div class="content">
                <table>
                    <tbody>
                    <tr>
                        <td>

                            <h3>Stop getting mails about quotation <?php echo $_GET['qname']; ?></h3>

                            <p>
                            <form method="post" action="unsubquote.php">
                                <input type="hidden" id="quoteid" name="quoteid" value="<?php echo $_GET['qid']; ?>">
                                <div class="row">
                                    <label for="reason">Reason for Unsuscribe:</label>
                                    <select class="required select form-control input-lg" name="reason" id="reason" title="Please, let us know why you don't want the quotation" required="true">
                                        <option value=""> -- select an option -- </option>
                                        <option value="toexpensive">Too expensive</option>
                                        <option value="purchasedother">Purchased somewhere else</option>
                                    </select><br />
                                </div>
                                <p style="text-align: right;">
                                    <input type="submit" class="btn" value="Unsubscribe Now"/>
                                </p>
                            </form>
                            </p>
                            <!-- Callout Panel -->
                            <p class="callout">
                                D Brash & Sons Ltd, Heavy Industrial Division, Calibra House Splott Ind Est Cardiff CF24
                                5FF
                                VAT No: 262 2496 59 • Company Registration No: SC56784
                            </p>
                            <table class="social" width="100%">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="column" align="left">
                                            <tbody>
                                            <tr>
                                                <td>

                                                    <h5 class="">Connect with Us:</h5>

                                                    <p class=""><a href="http://www.facebook.com/DBrashSonsLtd?fref=ts"
                                                                   class="soc-btn fb">Facebook</a> <a
                                                            href="https://twitter.com/DBrashSons" class="soc-btn tw">Twitter</a>
                                                    </p>


                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="column" align="left">
                                            <tbody>
                                            <tr>
                                                <td>

                                                    <h5 class="">Contact Info:</h5>

                                                    <p>Phone: <strong>+44(0)29 2048 8124

                                                        </strong><br>
                                                        Email: <strong><a href="emailto:sales@brash-scales.com">sales@brash-scales.com</a></strong>
                                                    </p>

                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!-- /column 2 -->

                                        <span class="clear"></span>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- /social & contact -->

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /content -->

        </td>
        <td></td>
    </tr>
    </tbody>
</table>
<!-- /BODY -->
</body>
<script src="jquery-1.9.1.js" type="text/javascript"></script>
</html>
<?php } ?>