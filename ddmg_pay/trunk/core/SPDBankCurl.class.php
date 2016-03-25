<?php

class SPDBankCurl extends CurlModel {
    
    public function curlSpdSign($params = array()){
//         $params = iconv('UTF-8', 'GB2312', $params);
        Log::spdNotice("SPDBankCurl=============>>curlSpdSign request-params=##" . $params . "##");
        $data = self::sendRequestSpdSign('', $params);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    public function curlSpdSend($params = array()){
        Log::spdNotice("SPDBankCurl=============>>curlSpdSend request-params=##" . $params . "##");
        $data = self::sendRequestSpdSend('', $params);
        $data = substr($data,6);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    public function curlSpdSignV($params = array()){
        Log::spdNotice("SPDBankCurl=============>>curlSpdSignV request-params=##" . $params . "##");
        $data = self::sendRequestSpdSign('', $params, 'v');
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        return $data;
    }
    
    public function curlSpdRequest($params = '', $transCode = '', $masterID = '', $packetID = '' ){
        Log::spdNotice("SPDBankCurl=============>>curlSpdRequest request-params=##" . $params . "##");
        // 1.签名
//         $params = iconv('UTF-8', 'GB2312', $params);
//         Log::spdNotice("SPDBankCurl=============>>curlSpdRequest request-params(iconv)=##" . $params . "##");
        $data = self::sendRequestSpdSign('', $params);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        
        if("0" != strval( $data['head']['result'] ) ) {
            return "err-dataSign.数据签名错误。";
        }
        $signature = strval( $data['body']['sign'] );
        
        // 2.发送
        $param = "<?xml version='1.0' encoding='GB2312'?>"
                    . "<packet><head>"
                        . "<transCode>" . $transCode . "</transCode><signFlag>1</signFlag>"
                        . "<masterID>" . $masterID . "</masterID>"
                        . "<packetID>" . $packetID . "</packetID>"
                        . "<timeStamp>" . date('Y-m-d H:i:s',time()) . "</timeStamp>"
                        . "</head><body>" 
                        . "<signature>" . $signature  . "</signature>"
                    . "</body></packet>" ;
        $param = (strlen($param) + 6) . '  ' . $param;
        
        $data = self::sendRequestSpdSend('', $param);
        $data = substr($data,6);
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        
        if("AAAAAAA" != strval( $data['head']['returnCode'] ) ) {
            return "err-dataExec.数据执行错误。";
        }
        $param = strval( $data['body']['signature'] );
        
        $data = self::sendRequestSpdSign('', $param, 'v');
        $data = $this->gbk2utf8( $data );
        $data =  $this->xmlToArray($data);
        
        if("0" != strval( $data['head']['result'] ) ) {
            return "err-dataSignV.数据验签错误。";
        }
        
        return $data['body']['sic'];
    }
    
    public function xmlToArray( $xml )
    {
        return json_decode( json_encode( simplexml_load_string( $xml ) ), true );
    }
    
    public function gbk2utf8($str){
        $charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
        $charset = strtolower($charset);
        if('cp936' == $charset){
            $charset='GBK';
        }
        if("utf-8" != $charset){
            $str = iconv($charset,"UTF-8//IGNORE",$str);
        }
        return $str;
    }
    
}