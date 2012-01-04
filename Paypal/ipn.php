<?php
	function paypal_ipn(){
		if (!empty($_POST)){
			//======= Settings
			$payee_email = Configure::read('PAYPAL_BIZ_EMAIL');
			$payee_merchant_id = Configure::read('PAYPAL_BIZ_MERCHANT_ID');
			$required_amount = '0.01';
			$mail_to = '';
			$development_debug = true;
			//======= End Settings

			//Read the post from paypal and add 'cmd'
			$req = 'cmd=_notify-validate';

			$data = $_POST;

			foreach($data as $k => $v){
				$v = urlencode(stripslashes($v));
				$req .= "&{$k}={$v}";
			}

			mail($mail_to, 'PAYPAL TEST', $req);

			//Post back to paypal system to validate
			$header = '';
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

			//Assign POSTed values to local VARS
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$os1 = $_POST['option_selection1'];
			$os2 = $_POST['option_selection2'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];

			//$mail_headers = "From: $mail_headers\n";
			$mail_headers = '';
			$content = '';

			if ($fp){
				fputs($fp, $header . $req);
				while(!feof($fp)){
					$res = fgets($fp, 1024);
					$content .= $res;

					if (strcmp($res, 'VERIFIED') == 0){
						//Check if trans ID has already been used
						//@TODO: Db implementation of trans ID check

						//Completed payment
						if ($payment_status == 'Completed'){
							if ($receiver_email == $payee_email && $payment_amount > $required_amount && $payment_currency == 'USD'){
								//Save transaction
								App::import('Controller', 'Transactions');
								$Trans = new TransactionsController;
								$Trans->constructClasses();

								//Get trans options and parse out the rid, sid and uid
								$trans_options = $os1;
								$trans_options_array = explode('|', $trans_options);

								$rid = $trans_options_array[0];
								$sid = $trans_options_array[1];
								$uid = $trans_options_array[2];
								
								//Status "3" = COMPLETED
								$data = array(
									'amount' => $payment_amount, 
									'currency' => $payment_currency, 
									'type' => 1, 
									'code' => $txn_id, 
									'timestamp' => date('Y-m-d H:i:s'), 
									'uid' => $uid, 
									'sid' => $sid, 
									'options' => serialize($trans_options), 
									'status' => 3
								);
								$rs = $Trans->save_transaction($data);

								if (!$rs){
									mail($mail_to, 'Payment Record Failure', 'Failed to record payment, here is the information');
								}

								$mail_subject = 'Paypal IPN status completed';
								$mail_body = "Everything ok";
								mail($mail_to, $mail_subject, $mail_body);
							}
						}else{
								$mail_subject = 'Paypal IPN status NOT completed';
								$mail_body = "Everything NOT ok";
								mail($mail_to, $mail_subject, $mail_body);
						}
					}else if (strcmp($res, 'INVALID') == 0){
								$mail_subject = 'Paypal IPN status NOT completed - INVALID';
								$mail_body = "Everything NOT ok";
								mail($mail_to, $mail_subject, $mail_body);
					}
				}
			}

		}

	}
?>
