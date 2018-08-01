<?php 
	
	class shipwire{
		function __construct($email,$pass){
			$this->email = $email;
			$this->password = $password;

			// api url to call
			$this->apiurls['rate'] = 'https://api.shipwire.com/exec/RateServices.php';
			$this->apiurls['inventory'] = 'https://api.shipwire.com/exec/InventoryServices.php';
			$this->apiurls['order'] = 'https://api.shipwire.com/exec/FulfillmentServices.php';
			$this->apiurls['tracking'] = 'https://api.shipwire.com/exec/TrackingServices.php';
			
		}
		
		/*****************************************************
		*	Get Rate
		*-----------------------------------------------------
		* 	Returns the rate quote to the address supplied
		**
		*-----------------------------------------------------
		*	Variables (* = required) 
		**
		*	address *			Destination street address
		*	address2 *			Destination suite/apt No.
		* 	city *				Destination city
		* 	state *				Destination province/state
		*	country *			Destination country
		*	zip *				Destination postal/zip code
		*	items *				Array of items ('sku', 'qty' as subkeys)
		**
		*	item example array
		*		item[0] = array('sku' => '12345678', 'qty' => 1);
		*		item[1] = array('sku' => '87654321', 'qty' => 4);
		*		item[2] = array('sku' => '19283746', 'qty' => 99');
		*		
		*****************************************************/
		
		public function get_rate($address, $address2, $city, $state, $country, $zip, $items){
				$xml = '<RateRequest>
							<EmailAddress><![CDATA[' . $this->email . ']]></EmailAddress>
							<Password><![CDATA[' . $this->password . ']]></Password>
							<Order id="quote123">
							<Warehouse>00</Warehouse>
							<AddressInfo type="ship">
								<Address1><![CDATA[' . htmlentities($address) . ']]></Address1>
								<Address2><![CDATA[' . htmlentities($address2) . ']]></Address2>
								<City><![CDATA[' . htmlentities($city) . ']]></City>
								<State><![CDATA[' . htmlentities($state) . ']]></State>
								<Country><![CDATA[' . htmlentities($country) . ']]></Country>
								<Zip><![CDATA[' . htmlentities($zip) . ']]></Zip>
							</AddressInfo>';
							//items
							$count = 1;
							foreach($items AS $key=>$data){
								$xml .= '<Item num="'.$count++.'">';
								$xml .= '<Code>'.htmlentities($data['sku']).'</Code>';
								$xml .= '<Quantity>'.htmlentities($data['qty']) . '</Quantity>';
								$xml .= '</Item>';	
							}
							
					$xml.='</Order></RateRequest>';					
					
					return $this->runCurl('rate', $xml);
					


		}
		public function submit_order($orderArray){
				$xml = '<OrderList>
							<Username><![CDATA['.$this->email.']]></Username>
							<Password><![CDATA['.$this->password.']]></Password>
							<Server>Test</Server>
							<Referer>023YAHOO</Referer>
							<Order id="test-485">
								<Warehouse>00</Warehouse>
								<AddressInfo type="ship">
									<Name>
										<Full>Sheridan Rawlins</Full>
									</Name>
									<Address1>321 Foo bar lane</Address1>
									<Address2>Apartment #2</Address2>
									<City>Nowhere</City>
									<State>CA</State>
									<Country>US</Country>
									<Zip>12345</Zip>
									<Phone>555-444-3210</Phone>
									<Email>sheridan@rawlins.com</Email>
								</AddressInfo>
								<Shipping>GD</Shipping>
								<Item num="0">
									<Code>12345</Code>
									<Quantity>1</Quantity>
								</Item>
							</Order>
						</OrderList>';
		}
		public function track_order($order){
			
		}
		
		private runCurl($api, $xml,){
			
			$xml_request_encoded = ("RateRequestXML=" . $xml);

			$session = curl_init();
			curl_setopt($session, CURLOPT_URL, $this->apiurls[$api]);
			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-type”,”application/x-www-form-urlencoded"));
			curl_setopt($session, CURLOPT_POSTFIELDS, $xml_request_encoded);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($session, CURLOPT_TIMEOUT, 360);
			return curl_exec($session);	
		}
	}


?>