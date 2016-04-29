<?php
namespace Garan24\Gateway\Aruispay;
use \Garan24\Gateway\BaseConnector as BaseConnector;
use \Garan24\Gateway\Aruispay\Exception  as Garan24GatewayAruispayException;
use \Garan24\Interfaces\ITransaction as ITransaction;
class Sale extends BaseConnector{
    public function __construct($data=[]){
        parent::__construct(["endpoint"=>"1144"]);
        $this->_request_data = $data;
        $this->_method = "sale-form";
    }
    public function check(){
        $req = [
            "login" => $this->_merchant_login, //Merchant login name
            "client_orderid" => $this->_request_data["client_orderid"], //Merchant order identifier of the transaction for which the status is requested
            "orderid" => $this->_response_data["paynet-order-id"], //Order id assigned to the order by PaynetEasy
            "by-request-sn" => $this->_response_data["serial-number"], //Serial number assigned to the specific request by PaynetEasy. If this field exist in status request, status response return for this specific request.
            "control" => ""
        ];
        $req["control"] = $this->digest(
            $this->_merchant_login
            .$this->_request_data["client_orderid"]
            .$this->_response_data["paynet-order-id"]
            .$this->_merchant_key
        );
        $res = $this->query("status",$req);
        if(!isset($res["status"])){
            throw new Garan24GatewayAruispayException("Error in check order response. Wrong format",500);
        }
        if($res["status"]=="unknown"){
            throw new Garan24GatewayAruispayException("Error in check order response. Status = 'unknown'.",500);
        }
        if(in_array($res["status"], ["approved","declined","error","filtered"])) {
            $this->_response_data = array_merge($this->_response_data,$res);
            return true;
        }
        return false;
    }
    public function cancel(){
        $result = [];
        return $result;
    }
    protected function build(){
        $this->_request_data["merchant_control"] = $this->_merchant_key;
        $amount = $this->_request_data["amount"]*100;
        $this->_request_data["control"] = $this->digest(
            trim($this->_endpoint)
            .trim($this->_request_data["client_orderid"])
            .preg_replace("/[\.,\-\s]/","",$this->_request_data["amount"])
            .trim($this->_request_data["email"])
            .trim($this->_request_data["merchant_control"])
        );
    }
};
?>
