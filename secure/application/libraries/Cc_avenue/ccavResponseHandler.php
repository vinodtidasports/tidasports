<?php 
	include('Crypto.php');
	//error_reporting ( 0 );
	$workingKey = CCA_WORKING_KEY; // Working Key should be provided here.
	echo $encResponse = $_POST ["encResp"]; // This is the response sent by the CCAvenue Server
	$rcvdString = decrypt ( $encResponse, $workingKey ); // Crypto Decryption used as per the specified working key.
	$order_status = "";
	$decryptValues = explode ( '&', $rcvdString );
	$dataSize = sizeof ( $decryptValues );
	for($i = 0; $i < $dataSize; $i ++) {
		$information = explode ( '=', $decryptValues [$i] );
		$responseMap [$information [0]] = $information [1];
	}
	$order_status = $responseMap ['order_status'];
	$pymtOrderObj = new PymtOrder ();
	$paymentOrder = $pymtOrderObj->getPaymentOrderByOrderId ( $responseMap ['order_id'] );
	// this is to fool-proof check - checking if the paymentstatus is already posted
	// this can happen on page refresh in success page.
	$paymentStatus = $pymtOrderObj->getPaymentStatusByOrderId ( $responseMap ['order_id'] );
	if (empty ( $paymentStatus )) {
		$pymtOrderObj->addPymtStatus ( $responseMap ['order_id'], $rcvdString, $responseMap ['order_status'], $responseMap ['amount'] );
	}
	if ($order_status === "Success") {
		$institutionObj = new Institution ();
		if (empty ( $paymentStatus )) {
			$staffObj = new Staff ();
			$numberOfSms = $responseMap ['amount'] / SMS_COST;
			$newSmsCredit = $numberOfSms + $currentInstitution [0] ["sms_credit"];
			$institutionObj->updateSmsCredit ( $newSmsCredit, $currentInstitution [0] ["id"] );
			$pymtOrderObj = new PymtOrder ();
			$pymtOrderObj->updateSmsCredit ( $newSmsCredit, $responseMap ["order_id"] );
		}
		$instNow = $institutionObj->getByID ( $currentInstitution [0] ["id"]);
		echo "Thank you for shopping with us. Your transaction is successful and the Order ID is " . $responseMap ['order_id'] . 
		". Your current SMS credit balance is " . $instNow [0] ["sms_credit"].".";
	} else if ($order_status === "Aborted") {
		echo "Thank you for shopping with us. We will keep you posted regarding the status of your order.";
	} else if ($order_status === "Failure") {
		echo "Thank you for shopping with us. However, the transaction has been declined.";
	} else {
		echo "Security Error. Illegal access detected";
	}
?>