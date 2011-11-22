<?php
$path = '../../lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once('services/PayPalApi/PayPalAPIInterfaceServiceService.php');
require_once('PPLoggingManager.php');
session_start();

$logger = new PPLoggingManager('DoExpressCheckout');

$token =urlencode( $_REQUEST['token']);
$payerId=urlencode( $_REQUEST['PayerID']);

$orderTotal = new BasicAmountType();
$orderTotal->currencyID = $_SESSION['currencyID'];
$orderTotal->value = $_SESSION['amount'];

$itemDetails = new PaymentDetailsItemType();
$itemDetails->Name = 'sample item';
$itemDetails->Amount = $orderTotal;
$itemDetails->Quantity = '1';
$itemDetails->ItemCategory =  'Digital';

$PaymentDetails= new PaymentDetailsType();
$PaymentDetails->PaymentDetailsItem[0] = $itemDetails;

//$PaymentDetails->ShipToAddress = $address;
$PaymentDetails->OrderTotal = $orderTotal;
$PaymentDetails->PaymentAction = 'Sale';
$PaymentDetails->ItemTotal = $orderTotal;

$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
$DoECRequestDetails->PayerID = $payerId;
$DoECRequestDetails->Token = $token;
$DoECRequestDetails->PaymentDetails[0] = $PaymentDetails;

$DoECRequest = new DoExpressCheckoutPaymentRequestType();
$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
$DoECRequest->Version = '78.0';

$DoECReq = new DoExpressCheckoutPaymentReq();
$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;
$PayPal_service = new PayPalAPIInterfaceServiceService();
$DoECResponse = $PayPal_service->SetExpressCheckout($DoECReq);
//var_dump($DoECResponse);
if($DoECResponse->Ack == 'Success')
	{
?>
<html>
<script>
alert("Payment Successful")
top.dg.closeFlow();
</script>
<?php 
	}
	else 
	{
?>
<script>
alert("Payment failed")
top.dg.closeFlow();
</script>
<?php 
	}
?>


<script type="text/javascript"
	src="https://www.paypalobjects.com/js/external/dg.js"></script></head>
<body>
</body>
</html>
