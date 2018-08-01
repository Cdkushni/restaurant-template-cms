<?php

class Shopman{
	
	//global vars---------------------------------------------------------
	//	sets the following vars
	//	cart_name	-	name of shopping cart session
	//--------------------------------------------------------------------
	function __construct($cart_name){						
        $this->cart_name = $cart_name;
    }
	
	//getCart-------------------------------------------------------------
	//	returns cart session
	//--------------------------------------------------------------------
	function getCart(){
		if(!isset($_SESSION[$this->cart_name]) || $_SESSION[$this->cart_name] == ""){
			$_SESSION[$this->cart_name] = array();
		}
		return $_SESSION[$this->cart_name];
	}
	
	
	//addToCart----------------------------------------------------------
	//	adds product to cart
	//	requires product_id, product_name, price, quantity, shipping
	//  may also pass simple options array
	//	sample array: array('Size' => 'Small', 'Color' => 'Green', 'Type' => 'Ladies')
	//--------------------------------------------------------------------
	function addToCart($product_id, $product_name, $price, $quantity, $shipping, $options=''){
		
		//get cart
		$cart = $this->getCart();
		
		//if it already exists in cart, just add to quantity
		$exists = false;
		if(count($cart) > 0){
			foreach($cart as $key=>$data){
				if($data['Product ID'] == $product_id && $data['Options'] == $options){
					$_SESSION[$this->cart_name][$key]['Quantity'] = $data['Quantity'] += $quantity;
					$exists = true;	
				}
			}
		}
		
		//if it does not exist, add to cart
		if($exists == false){
			$cartitem = array(
				'Product ID' => $product_id,
				'Product Name' => $product_name,
				'Price' => $price,
				'Quantity' => $quantity,
				'Shipping' => $shipping,
				'Options' => $options	  
			);
			array_push($_SESSION[$this->cart_name], $cartitem);
		}
	
	}
	
	//deleteFromCart------------------------------------------------------
	//	deletes product from cart
	//	requires index of cart item
	//--------------------------------------------------------------------
	function deleteFromCart($key){
		unset($_SESSION[$this->cart_name][$key]);
	}
	
	//clearCart-----------------------------------------------------------
	//	clears entire shopping cart
	//--------------------------------------------------------------------
	function clearCart(){
		unset($_SESSION[$this->cart_name]);
	}
	
	//getGST--------------------------------------------------------------
	//	returns GST rate as a decimal (5% would be 0.05)
	//	requires ship to province code
	//--------------------------------------------------------------------
	function getGST($province){
		
		$taxesqry = mysql_query("SELECT * FROM tax WHERE province = '$province'");
		$taxes = mysql_fetch_array($taxesqry);
		$GST = $taxes['GST'];
		
		return $GST;
	}
	
	//getPST--------------------------------------------------------------
	//	returns PST rate as a decimal (7% would be 0.07)
	//	requires ship to province code
	//--------------------------------------------------------------------
	function getPST($province){
		
		$taxesqry = mysql_query("SELECT * FROM tax WHERE province = '$province'");
		$taxes = mysql_fetch_array($taxesqry);
		$PST = $taxes['PST'];
		
		return $PST;
	}
	
	//getTaxes------------------------------------------------------
	//	requires order total, ship to province, GST and PST rates
	//--------------------------------------------------------------------
	function getTaxes($totalprice, $province){
		
		$GST = $this->getGST($province);
		$PST = $this->getPST($province);
		
		if($province != "QC"){
			$GST = $totalprice*$GST;
			$PST = $totalprice*$PST;
			$totaltax = $GST+$PST;
		
		//quebec
		}else{
			$PST = ($totalprice*($GST+1))*$PST;
			$GST = $totalprice*$GST;
			$totaltax = $GST+$PST;
		}
		
		return $totaltax;
	}


	//getDiscount---------------------------------------------------------
	//	returns discount message and discount dollar amount as array
	//	requires promocode and order total
	//--------------------------------------------------------------------
	function getDiscount($promocode, $totalprice){
		
		if(trim($promocode) != ""){
			$promoqry = mysql_query("SELECT * FROM promocodes WHERE code = '$promocode'");
			$ispromo = mysql_num_rows($promoqry);
			if($ispromo > 0){
				$pro = mysql_fetch_array($promoqry);
				$promo['message'] = $pro['amount']. "% off";
				$promo['amount'] = number_format(($pro['amount']/100)*str_replace(",", "", $totalprice), 2);
				
			}else{
				$promo['message'] = "Invalid Code";
				$promo['amount'] = number_format(0, 2);
			}
		}else{
			$promo['message'] = "";
			$promo['amount'] = number_format(0, 2);
		}
		return $promo;
	}
	
	
	//getShippingRate---------------------------------------------------------
	//	returns UPS standard shipping cost per item
	//	requires UPS access key, account number, username, password, shipfrom, shipto, package details
	//	shipfrom array: array('company' => 'Company Name', 'address' => '123 Street', 'city' => 'Edmonton', 'province' => 'AB', 'postalcode' => 'T5T5T5', 'country' => 'CA')
	//	shipto array: array('address' => '123 Street', 'city' => 'Edmonton', 'province' => 'AB', 'postalcode' => 'T5T5T5', 'country' => 'CA')
	//	pkgdetails array: array('length' => '12', 'width' => '12', 'height' => '3', 'weight' => '5')
	//	can set to live environment or test environment
	//--------------------------------------------------------------------
	function getShippingRate($accesskey, $accountnum, $username, $password, $shipfrom, $shipto, $pkgdetails, $live=''){
	
		if($live == true){
			//$apiurl = "https://onlinetools.ups.com/ups.app/xml/Rate";
		}else{
			$apiurl = "https://wwwcie.ups.com/ups.app/xml/Rate";
		}
		
		$request = "<?xml version=\"1.0\" ?>
		<AccessRequest xml:lang='en-US'>
			<AccessLicenseNumber>" .$accesskey. "</AccessLicenseNumber>
			<UserId>" .$username. "</UserId>
			<Password>" .$password. "</Password>
		</AccessRequest>
		<?xml version=\"1.0\" ?>
			<RatingServiceSelectionRequest>
				<Request>
					<RequestAction>Rate</RequestAction>
					<RequestOption>Rate</RequestOption>
				</Request>
				<Shipment>
					<Shipper>
						<Name>" .$shipfrom['company']. "</Name>
						<ShipperNumber>" .$accountnum. "</ShipperNumber>
						<Address>
							<AddressLine1>" .$shipfrom['address']. "</AddressLine1>
							<City>" .$shipfrom['city']. "</City>
							<StateProvinceCode>" .$shipfrom['province']. "</StateProvinceCode>
							<PostalCode>" .$shipfrom['postalcode']. "</PostalCode>
							<CountryCode>" .$shipfrom['country']. "</CountryCode>
						</Address>
					</Shipper>
					<ShipTo>
						<Address>
							<AddressLine1>" .$shipto['address']. "</AddressLine1>
							<City>" .$shipto['city']. "</City>
							<StateProvinceCode>" .$shipto['province']. "</StateProvinceCode>
							<PostalCode>" .$shipto['postalcode']. "</PostalCode>
							<CountryCode>" .$shipto['country']. "</CountryCode>
						</Address>
					</ShipTo>
					<Service>
						<Code>11</Code>
						<Description>UPS Standard</Description>
					</Service>
					<Package>
						<PackagingType>
							<Code>02</Code>
							<Description>Package</Description>
						</PackagingType>
						<Dimensions>
							<UnitOfMeasurement>
								<Code>IN</Code>
							</UnitOfMeasurement>
							<Length>" .number_format($pkgdetails['length'], 1). "</Length>
							<Width>" .number_format($pkgdetails['width'], 1). "</Width>
							<Height>" .number_format($pkgdetails['height'], 1). "</Height>
						</Dimensions>
						<PackageWeight>
							<UnitOfMeasurement>
								<Code>LBS</Code>
							</UnitOfMeasurement>
							<Weight>" .number_format($pkgdetails['weight'], 1). "</Weight>
						</PackageWeight>
					</Package>
				</Shipment>
			</RatingServiceSelectionRequest>";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $apiurl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml;'));
			$result = curl_exec($ch);
			curl_close($ch);
			
			return simplexml_load_string($result);
	}


}
?>