<?php
/* ============================================================
 * php-binance-api
 * https://github.com/sslayo/BNB
 * ============================================================
 * Released under the MIT License
 * ============================================================ */

const url = 'https://www.binance.com/api/';
const BR = '<br>';
function setKeys($apiKey, $secretKey) {
	
	if ($apiKey == "" || $secretKey == "") {
		echo "Error: please set both keys!";
		
	} else {
		echo "Key successfully file created!".BR."API key: $apiKey Secret Key: $secretKey";
		$myfile = fopen("keys.php", "w") or die("Unable to open file!");
		$txt = "<?php \n$" . "apiKey=\"" . $apiKey . "\";\n" . "$" . "secretKey=\"" . $secretKey . "\";\n?>";
		fwrite($myfile, $txt);
		fclose($myfile);
	}

}

function getMarkets($markets="ALL") {
	$coins=array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
	//Get all available markets
	curl_setopt($ch, CURLOPT_URL,"https://www.binance.com/api/v1/ticker/allPrices");
	$result = curl_exec($ch);		
	//Decode json
	$json = json_decode($result);
	curl_close ($ch); // Close cURL
	
	for ($i=0; $i<sizeof($json); $i++) {
	
	$coins[] = array( 'symbol'=>$json[$i]->symbol,
					  'last'=>$json[$i]->price
		  );
		  
	}
	$BTCmarkets=array();
	$ETHmarkets=array();
	$USDTmarkets=array();

	foreach($coins as $coin) {
		if (substr($coin['symbol'],-3) == "BTC") {
			
			$BTCmarkets[]=array(
							'symbol'=>$coin['symbol'],
							'ticker'=>(substr($coin['symbol'],0,-3)),
							'price'=>$coin['last']
							);
			
		} else if (substr($coin['symbol'],-3) == "ETH")  {
			$ETHmarkets[]=array(
							'symbol'=>$coin['symbol'],
							'ticker'=>(substr($coin['symbol'],0,-3)),
							'price'=>$coin['last']		
							);
		} else if (substr($coin['symbol'],-4) == "USDT") {
			$USDTmarkets[]=array(
							'symbol'=>$coin['symbol'],
							'ticker'=>(substr($coin['symbol'],0,-4)),
							'price'=>$coin['last']
							);	
		}
	}
	if (($markets=="ALL") || ($markets=="BTC") || ($markets=="btc")) {
		echo "BTC MARKETS".BR;
	foreach ($BTCmarkets as $BTCmarket){
		echo $BTCmarket['symbol']. " - ".$BTCmarket['price'].BR; 
		}
	}
	if ($markets=="ALL" || $markets=="ETH" || $markets=="eth") {
		echo "ETH MARKETS".BR;
	foreach ($ETHmarkets as $ETHmarket){
		echo $ETHmarket['symbol']. " - ".$ETHmarket['price'].BR; 
		}
	}
		echo "USDT MARKETS".BR;
	if ($markets=="ALL" || $markets=="USDT" || $markets=="usdt" || $markets=="usd") {
	foreach ($USDTmarkets as $USDTmarket){
		echo $USDTmarket['symbol']. " ". $USDTmarket['price'].BR; 
		}
	}
}

function getMarket($symbol="BNBBTC"){
	$ch = curl_init(); // Initialise cURL
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL Cert Verification False, we don't need to sign this
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	 // Return results
	curl_setopt($ch, CURLOPT_URL,"https://www.binance.com/api/v1/ticker/24hr?symbol=".$symbol); //Set URL
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	//Decode json
	$json = json_decode($result); // Results returned in jSON, decode and store in $json
		
	$price = $json->lastPrice; //Access the object lastPrice
	echo "Price of $symbol: $price"; 
		
}

function getDepth($symbol="BNBBTC") {
	$ch = curl_init(); // Initialise cURL
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // We don't need to sign this
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	 // Return results
	curl_setopt($ch, CURLOPT_URL,"https://www.binance.com/api/v1/depth?symbol=".$symbol); // Set URL
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	$json = json_decode($result); //Decode returned jSON
	
	// Example build on depth graph//
	$totalbids = (sizeof($json->bids)-1); // See how many BIDS we have 
	$totalasks = (sizeof($json->bids)-1); // See how many ASKS we have

	$lowestbid = $json->bids[$totalbids][0]; // Define lowest BID 
	$highestask = $json->asks[$totalasks][0]; // Define highest ASK
	
	for ($i=0; $i<($totalasks+1); $i++) {
		
		$bids[] = array (
		'bidprice'=>$json->bids[$i][0],
		'amount'=>$json->bids[$i][1],
		'null'=>0
		);
		$asks[] = array (
		'askprice'=>$json->asks[$i][0],
		'amount'=>$json->asks[$i][1],
		'null'=>0
		); 
}
		$totalbids=0;
		$totalasks=0;
		print "<html>\n";
		print "  <head>\n";
		print "    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
		print "    <script type=\"text/javascript\">\n";
		print "      google.charts.load('current', {'packages':['corechart']});\n";
		print "      google.charts.setOnLoadCallback(drawChart);\n";
		print "\n";
		print "      function drawChart() {\n";
		print "        var data = google.visualization.arrayToDataTable([\n";
		print "          ['PRICE',  'BIDS', 'ASKS'],";
		
		foreach($bids as $bid) {
		  echo "[" . $bid['bidprice'] . ", " . ($totalbids+=$bid['amount']) . ", " . "0],";
		}  
		  foreach($asks as $ask) {
		 echo "[" . $ask['askprice'] . ", " . "0, ". ($totalasks+=$ask['amount']) . "],";
		}
		print " ]);\n";
		print "\n";
		print "        var options = {\n";
		print "          title: '$symbol Order Book',\n";
		print "          vAxis: {title: ''},\n";
		print "		  hAxis: {title: '', viewWindow: { min:";
		echo $lowestbid;
		print ", max:";
		echo $highestask;
		print "}},\n";
		print "          isStacked: false,\n";
		print "		  colors: ['#00ff00', '#e0440e'],\n";
		print "		  legend: 'none'\n";
		print "        };\n";
		print "\n";
		print "        var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart_div'));\n";
		print "\n";
		print "        chart.draw(data, options);\n";
		print "      }\n";
		print "    </script>\n";
		print "  </head>\n";
		print "  <body>\n";
		print "    <div id=\"chart_div\" style=\"width: 900px; height: 500px;\"></div>\n";
		print "  </body>\n";
		print "</html>";
		
}

function buyLimitOrder($symbol,$quantity,$price,$timeinForce="GTC") {
	include 'keys.php';	//GET KEYS FILE
	$ch = curl_init(); // Initiate cURL
	//$symbol = "BNBBTC";
	$side = "BUY"; // "BUY" or "SELL"
	//$timeinForce = "GTC"; // "GTC"(Good till Cancelled) or "IOC"(Immediate or Cancel)
	//$quantity = 2; // How many you want to buy
	//$price = 0.000034; // How much to pay
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$type = "LIMIT"; // "LIMIT" or "MARKET" (Best Market Price)
	$params="symbol=$symbol&side=$side&type=$type&timeInForce=$timeinForce&quantity=$quantity&price=$price&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Recieve response from API Request
	curl_setopt($ch, CURLOPT_POST, 1); // Set HTTP method to POST
	$test = "https://www.binance.com/api/v3/order?$params&signature=$signiture";
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check for errors
		echo 'Error:' . curl_error($ch); // Display error
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print results
}

function sellLimitOrder($symbol,$quantity,$price,$timeinForce="GTC") {
	include 'keys.php';	//GET KEYS FILE
	$ch = curl_init(); // Initiate cURL
	//$symbol = "BNBBTC";
	$side = "SELL"; // "BUY" or "SELL"
	//$timeinForce = "GTC"; // "GTC"(Good till Cancelled) or "IOC"(Immediate or Cancel)
	//$quantity = 2; // How many you want to buy
	//$price = 0.000034; // How much to pay
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$type = "LIMIT"; // "LIMIT" or "MARKET" (Best Market Price)
	$params="symbol=$symbol&side=$side&type=$type&timeInForce=$timeinForce&quantity=$quantity&price=$price&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Recieve response from API Request
	curl_setopt($ch, CURLOPT_POST, 1); // Set HTTP method to POST
	$test = "https://www.binance.com/api/v3/order?$params&signature=$signiture";
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check for errors
		echo 'Error:' . curl_error($ch); // Display error
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print results
}

function buyMarketOrder($symbol, $quantity) {
	include 'keys.php';	//GET KEYS FILE
	$ch = curl_init(); // Initiate cURL
	//$symbol = "BNBBTC"; // Set which pair you want to BUY or SELL
	$side = "BUY"; // "BUY" or "SELL"
	$type = "MARKET"; // "LIMIT" or "MARKET" (Best Market Price)
	//$quantity = 1; // How many you want to buy
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$params="symbol=$symbol&side=$side&type=$type&quantity=$quantity&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Recieve response from API Request
	curl_setopt($ch, CURLOPT_POST, 1); // Set HTTP method to POST
	$test = "https://www.binance.com/api/v3/order?$params&signature=$signiture";
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check for errors
		echo 'Error:' . curl_error($ch); // Display error
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print results
		
}

function sellMarketOrder($symbol, $quantity) {
	include 'keys.php';	//GET KEYS FILE
	$ch = curl_init(); // Initiate cURL
	//$symbol = "BNBBTC"; // Set which pair you want to BUY or SELL
	$side = "SELL"; // "BUY" or "SELL"
	$type = "MARKET"; // "LIMIT" or "MARKET" (Best Market Price)
	//$quantity = 1; // How many you want to buy
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$params="symbol=$symbol&side=$side&type=$type&quantity=$quantity&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Recieve response from API Request
	curl_setopt($ch, CURLOPT_POST, 1); // Set HTTP method to POST
	$test = "https://www.binance.com/api/v3/order?$params&signature=$signiture";
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check for errors
		echo 'Error:' . curl_error($ch); // Display error
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print results
	
}

function orderStatus ($symbol, $orderID) {
	include 'keys.php';	//GET KEYS FILE
	//$symbol = "BNBBTC"; // Set which pair you want to get orders for
	//$orderID = "12341234";
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	
	$ch = curl_init(); // Initialise cURL
	$params = "symbol=$symbol&orderId=$orderID&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return values
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // HTTP Method to GET

	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers

	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print Results	
	
}

function cancelOrder($symbol, $orderID) {
	include 'keys.php';	//GET KEYS FILE	
	//$symbol = "BNBBTC"; // Set which pair you want to get orders for
	//$orderID = 6338879; // Set Order IDvvvvvvv
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$params = "symbol=$symbol&orderId=$orderID&timestamp=$serverTimestamp";  // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	$ch = curl_init(); // Initialise cURL
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/order?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return values
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Set HTTP Method to "DELETE"
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print Results
}

function currentPosition() {
	include 'keys.php';	//GET KEYS FILE	
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$ch = curl_init(); // Initialise cURL
	$params = "timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/account?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return values
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // HTTP Method to GET
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print Results
}

function openOrders($symbol) {
	include 'keys.php';	//GET KEYS FILE	
	//$symbol = "BNBBTC"; // Set which pair you want to get orders for
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$ch = curl_init(); // Initialise cURL
	$params = "symbol=$symbol&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/openOrders?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return values
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // HTTP Method to GET
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print Results
}

function orderHistory($symbol) {
	//$symbol = "BNBBTC"; // Set which pair you want to get orders for
	$serverTimestamp = time()*1000; // Take current UNIX timestamp and convert to miliseconds
	$ch = curl_init(); // Initialise cURL
	$params = "symbol=$symbol&timestamp=$serverTimestamp"; // Set required paramaters
	$signiture = hash_hmac("sha256", $params, $secretKey); // Take the parameters, and sign them with the Secret Key
	curl_setopt($ch, CURLOPT_URL, "https://www.binance.com/api/v3/allOrders?$params&signature=$signiture"); // Set URL + Parameters + Signiture
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return values
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // HTTP Method to GET
	$headers = array(); // Set up our Headers
	$headers[] = "X-Mbx-Apikey: $apiKey"; // Put the API Key in HTTP Header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Enable Headers
	$result = curl_exec($ch); // Execute cURL command
	if (curl_errno($ch)) { // Check if any errors
		echo 'Error: ' . curl_error($ch); // Display Errors
	}
	curl_close ($ch); // Close cURL
	print_r($result); // Print Results
	
}

getMarkets();

?>