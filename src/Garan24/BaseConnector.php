<?php
namespace Garan24;

class BaseConnector implements Interfaces\IConnector{
    protected $_curl;
    protected $_curl_options;
    protected $_url;
    protected $_endpoint;
    protected $_merchant_key;
    protected $_merchant_login;
    protected $_debug = false;
    public function setDebugMode($b = true){
        $this->_debug=$b;
    }
    public function __construct($opts = []){
        $this->_url = (!isset($opts["url"]))?"https://sandbox.ariuspay.ru/paynet/api/v2/":$opts["url"];
        $this->_endpoint = (!isset($opts["endpoint"]))?"1144":$opts["endpoint"];
        $this->_merchant_key = (!isset($opts["merchantKey"]))?"99347351-273F-4D88-84B4-89793AE62D94":$opts["merchantKey"];
        $this->_merchant_login = (!isset($opts["merchantLogin"]))?"GARAN24":$opts["merchantLogin"];
    }
/*******************************************************************************
 * Производит перенаправление пользователя на заданный адрес
 *
 * @param string $url адрес
 ******************************************************************************/
    public function redirect($url){
        Header("HTTP 302 Found");
        Header("Location: ".$url);
        die();
    }
/*******************************************************************************
 * Совершает запрос с заданными данными по заданному адресу. В ответ
 * ожидается JSON
 *
 * @param string $method GET|POST
 * @param array|null $data POST-данные
 *
 * @return array
 ******************************************************************************/
    public function query($method,$data = null){
	       $query_data = "";
           $curlOptions = [
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_POST => true,
               CURLOPT_POSTFIELDS => http_build_query($data)
           ];
           $curl = curl_init($this->_url."/".$method."/".$this->_endpoint);
           curl_setopt_array($curl, $curlOptions);
           $fp=fopen('../contur-focus-curl-'.date("Y-m-d").'.log', 'wa');
           curl_setopt($curl,CURLOPT_VERBOSE, 1);
           curl_setopt($curl, CURLOPT_STDERR, $fp);
           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
           $response = curl_exec($curl);
           parse_str($response,$result);
           if($this->_debug){
               print("**********************************************************\n");
               print("URL:[".$this->_url.$method."/".$this->_endpoint."]\n");
               print_r($data);
               print_r($result);
               print("**********************************************************\n");
           }
           //fclose($fp);
           return $result;
           //return json_decode($result, 1);
       }
};
?>
