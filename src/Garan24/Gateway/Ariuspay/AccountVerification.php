<?php
namespace Garan24\Gateway\Aruispay;
use \Garan24\Gateway\BaseConnector as BaseConnector;
use \Garan24\Gateway\Aruispay\Exception  as Garan24GatewayAruispayException;
use \Garan24\Interfaces\ITransaction as ITransaction;
class AccountVerification extends BaseConnector{
    public function __construct($data=[]){
        parent::__construct(["endpoint"=>"1144"]);
        $this->_request_data = $data;
        $this->_method = "account-verification";
    }
    protected function build(){
        $this->_request_data["merchant_control"] = $this->_merchant_key;
        $this->_request_data["control"] = $this->digest(
            trim($this->_endpoint)
            .trim($this->_request_data["client_orderid"])
            .trim($this->_request_data["email"])
            .trim($this->_request_data["merchant_control"])
        );
    }
};
?>
