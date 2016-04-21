<?php
namespace Garan24;

class BaseConnector implements Interfaces\IConnector{
    protected $_curl;
    protected $_curl_options;
    protected $_url;
    protected $_endpoint;
    protected $_merchant_key;
    public function __construct($opts = ["url" => null,"endpoint"=>null,"merchantKey"=>null]){
        $this->_url = (is_null($opts["url"]))?"https://sandbox.ariuspay.ru/paynet/api/v2/":$opts["url"];
        $this->_endpoint = (is_null($opts["endpoint"]))?"1144 ":$opts["endpoint"];
        $this->_merchant_key = (is_null($opts["merchantKey"]))?"99347351-273F-4D88-84B4-89793AE62D94 ":$opts["merchantKey"];
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
           $fp=fopen('../logs/contur-focus-curl-'.date("Y-m-d").'.log', 'wa');
           curl_setopt($curl,CURLOPT_VERBOSE, 1);
           curl_setopt($curl, CURLOPT_STDERR, $fp);
           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
           $result = curl_exec($curl);
           fclose($fp);
           return json_decode($result, 1);
       }
/*******************************************************************************
 * Вызов метода REST.
 *
 * @param string $method вызываемый метод
 * @param array $data параметры вызова метода
 *
 * @return array
 ******************************************************************************/
    public function call($method, $data){
        return $this->query($method, $data);
    }
};
?>
