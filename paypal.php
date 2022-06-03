This is an Example for how to integrate Paypal REST APIs to your we application using PHP 
In this Example I would like to show you the steps for sending API request CURL post .

There is two Options in PAYPAL one SANDBOX AND LIVE . you can use sandbox for testing environment and Live for Live
**STEP 1 Create CLIENT ID and Secret Key** 
for that you have to follow below steps

1) https://developer.paypal.com/developer/applications  click on this link it will ask for login please login with your login credentials
2) Login to the Application 
3) in the left hand side you can see a link called My App and Credentials click on that 
4) There is an Option call Create APP button click on that 
5) Enter your New App Name and select the 1st option then click create APP
Wait for a while for creating your client ID and Secret Key once it created you can follow the below code to do the integration.

**STEP 2 generate a access key** 

```
<?PHP 
$ch = curl_init();
$clientId = "ARGXQKt**************************************************HU4YUtwX";
$secret = "ELizULivW*******************************************u9Vcku4rwE";

curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSLVERSION , 6); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$result = curl_exec($ch);

if(empty($result))die("Error: No response.");
else
{
    $json = json_decode($result);
    //echo $result;
    print_r($json->access_token); // this the the token  PAYPAL generate for the particular transaction
}

curl_close($ch); 

?>

```
**STEP 3 to generate the order by using the generated access token**

```
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sandbox.paypal.com/v2/checkout/orders",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{
  "intent": "CAPTURE",
  "purchase_units": [
    {
      "reference_id": 'ORDERNO-0001",
      "amount": {
        "currency_code": "USD",
        "value": "150.00"
      }
    }
  ],
  "application_context": {
    "return_url": "https://yourdomainname.com/paypal/return_url.php",
    "cancel_url": "https://yourdomainname.com/paypal/cancel_url.php"
  }
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'accept-language: en_US',
    'authorization: Bearer '.$json->access_token, // **here you can see the generated token use for creating a order transaction to the paypal payment gateway.**
    'content-type: application/json'
  ),
));

$response = curl_exec($curl);  // **In this response you will get a link with a order id from the PAYPAL**
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
  $json_data = json_decode($response);
  echo "SAMPLE : ".$json_data->id;
  
  print_r("<a href='https://www.sandbox.paypal.com/checkoutnow?token=".$json_data->id."'>Check Out Now </a>");
  // **Here you are moving to the PAYPAL payment gateway to do the payment with the order id **
}

?>


```
In the above example you can see how the post is working and you can see the list of API hits in the option in the left side of the PAYPAL panel.


Enjoy coding :-)

