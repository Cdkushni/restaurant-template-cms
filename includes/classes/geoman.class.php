<?php 
/***********************************
		   geoMAN
	   Version: 1	 
***********************************/

/**********************************
 * Example geocode Response
/**********************************
{
  "name": "#100, 10665 Jasper Avenue Edmonton Alberta Canada",
  "Status": {
    "code": 200,
    "request": "geocode"
  },
  "Placemark": [ {
    "id": "p1",
    "address": "10665 Jasper Ave NW, Edmonton, AB T5J 3S9, Canada",
    "AddressDetails": {
   "Accuracy" : 8,
   "Country" : {
      "AdministrativeArea" : {
         "AdministrativeAreaName" : "AB",
         "Locality" : {
            "LocalityName" : "Edmonton",
            "PostalCode" : {
               "PostalCodeNumber" : "T5J 3S9"
            },
            "Thoroughfare" : {
               "ThoroughfareName" : "10665 Jasper Ave NW"
            }
         }
      },
      "CountryName" : "Canada",
      "CountryNameCode" : "CA"
   }
},
    "ExtendedData": {
      "LatLonBox": {
        "north": 53.5422690,
        "south": 53.5395710,
        "east": -113.5027720,
        "west": -113.5054700
      }
    },
    "Point": {
      "coordinates": [ -113.5041210, 53.5409200, 0 ]
    }
  } ]
}
*/

class Geoman{
	
	protected $apiKey;
	protected $earthRadius;
	
	var $locatehost = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}';
	var $currency;

	public function __construct() {
		$this->earthRadius = 3961.3;
		$this->ip = '';
		$this->city = '';
		$this->region = '';
		$this->areaCode = '';
		$this->dmaCode = '';
		$this->countryCode = '';
		$this->countryName = '';
		$this->continentCode = '';
		$this->latitude = '';
		$this->longitude = '';
		$this->currencyCode = '';
		$this->currencySymbol = '';
		$this->currencyConverter = '';
		$this->kilometers = true;

	}
	

	/**
	 * Loads JSON string from address
	 */
	protected function getJSON($address) {
		// $contents = file_get_contents($address);
		// return $contents;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
      curl_setopt($ch, CURLOPT_URL, $address);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
		     
	}

	public function convert_to_miles($kms){
		return number_format(($kms/1.609344),1);
	}
	/**
	 * Find the distance between the two latitude and longitude coordinates
	 * Where the latitude and longitude coordinates are in decimal degrees format.
	 */
	public function haversinDistance($lat1, $long1, $lat2, $long2)
	{
		$lat1 = deg2rad( $lat1 );
		$lat2 = deg2rad( $lat2 );
		$long1 = deg2rad( $long1);
		$long2 = deg2rad( $long2);

		$dlong = $long2 - $long1;
		$dlat = $lat2 - $lat1;

		$sinlat = sin( $dlat/2 );
		$sinlong = sin( $dlong/2 );

		$a = ($sinlat * $sinlat) + cos( $lat1 ) * cos( $lat2 ) * ($sinlong * $sinlong);
		$c = 2 * asin( min( 1, sqrt( $a ) ));
		if ($this->kilometers){
			return ($this->earthRadius*$c)*1.60934;	
		}else{
			return $this->earthRadius * $c;
		}
	}

	/**
	 * Find the distance between two latitude and longitude points using the
	 * spherical law of cosines.
	 */
	public function sphericalLawOfCosinesDistance( $lat1, $long1, $lat2, $long2 )
	{
		$lat1 = deg2rad( $lat1 );
		$lat2 = deg2rad( $lat2 );
		$long1 = deg2rad( $long1);
		$long2 = deg2rad( $long2);
		
		if ($this->kilometers){
			return ($this->earthRadius * acos(
						sin( $lat1 ) * sin( $lat2 ) +
						cos( $lat1 ) * cos( $lat2 ) * cos( $long2 - $long1 )
					))*1.60934;
		}else{
			return $this->earthRadius * acos(
				sin( $lat1 ) * sin( $lat2 ) +
				cos( $lat1 ) * cos( $lat2 ) * cos( $long2 - $long1 )
			);	
		}
		
	}

	/**
	 * Find the distance between two latitude and longitude coordinates
	 */
	public function distanceBetween($lat1, $long1, $lat2, $long2)
	{
		return $this->haversinDistance($lat1, $long1, $lat2, $long2);
	}
	
	/**
	 * gets google directions
	 */
	public function directions($address1,$address2){
		
		$url = 'http://maps.googleapis.com/maps/api/directions/json?origin='.urlencode($address1).'&destination='.urlencode($address2).'&sensor=false';
		$file = $this->getJSON($url);
		
		return json_decode($file);
			
	}
	/**
	 * Geocodes address
	 */
	public function geocode($address)
	{
		$url = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=json&oe=UTF-8";
		$file = $this->getJSON($url);
		return json_decode($file);

	}
	
	/**
	 * Locates user via IP
	 */
	public function ip_locate($ip = NULL){
		
		if (is_null($ip)) {
			$ip = $this->get_real_ip_address();
		}
		
		$locatehost = str_replace( '{IP}', $ip, $this->locatehost );
		$locatehost = str_replace( '{CURRENCY}', $this->currency, $locatehost );
		
		$data = array();
		
		$response = $this->fetch($locatehost);
		
		$data = unserialize($response);
		
		//set the geoPlugin vars
		$this->ip = $ip;
		$this->city = $data['geoplugin_city'];
		$this->region = $data['geoplugin_region'];
		$this->areaCode = $data['geoplugin_areaCode'];
		$this->dmaCode = $data['geoplugin_dmaCode'];
		$this->countryCode = $data['geoplugin_countryCode'];
		$this->countryName = $data['geoplugin_countryName'];
		$this->continentCode = $data['geoplugin_continentCode'];
		$this->latitude = $data['geoplugin_latitude'];
		$this->longitude = $data['geoplugin_longitude'];
		$this->currencyCode = $data['geoplugin_currencyCode'];
		$this->currencySymbol = $data['geoplugin_currencySymbol'];
		$this->currencyConverter = $data['geoplugin_currencyConverter'];
		
		
		return array('lat'=>$this->latitude,'long'=>$this->longitude);
	}	
	
	private function fetch($host) {

		if (function_exists('curl_init')) {
						
			//use cURL to fetch data
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if (ini_get('allow_url_fopen')) {
			
			//fall back to fopen()
			$response = file_get_contents($host, 'r');
			
		} else {

			trigger_error ('geoPlugin class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ', E_USER_ERROR);
			return;
		
		}
		
		return $response;
	}
	//get users real ip address (with forwarded)
	private function get_real_ip_address(){
		if (!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else{
		 $ip = $_SERVER["REMOTE_ADDR"];
		}
		return $ip;
	}
}

?>