<?php

/**
 * 
 * @author Ardhana <ardzz@indoxploit.or.id>
 * @date: 2019-05-18 01:32:18
 * @last modified by: Ardhana <ardzz@indoxploit.or.id>
 * @last modified time: 2019-05-19 12:33:04
 * 
 */

namespace HttpRequest;

interface interfaceHttpRequest{

    public function execute();
    public function getBody();
    public function getCookies();
    public function getHeaders();
    public function getRealHeaders();
    public function getTotalTime();

}

class Main implements interfaceHttpRequest{
    
    /**
     * @var string
     */

    public $url = NULL;

    /**
     * @var array|string
     */

    public $parameter = NULL;

    /**
     * @var int|string
     */

    public $timeout = 15; // 15s

    /**
     * @var string
     * @example 127.0.0.1:8080
     */

    public $proxy = NULL;

    /**
     * 
     * Autentikasi proxy, menggunakan password
     * 
     * @var string
     * 
     */

    public $proxy_auth = NULL;

    /**
     * Request Headers
     * 
     * Example usage:
     * 
     * $httpRequest = new \HttpRequest\Main;
     * $httpRequest->isGET();
     * $httpRequest->url = "example.com";
     * $httpRequest->headers = ["Cookie: csrf=445082SSQQKDLS;"];
     * $httpRequest->execute();
     * var_dump($httpRequest->get());
     * 
     * @var array
     * 
     */

    public $headers = NULL;

    /**
     * @var string
     */

    public $user_agent = NULL;

    /**
     * @var string|array
     */

    public $postdata = NULL;

    function __construct(){
        if (!function_exists('curl_version')) {
            exit("cURL isn't installed!" . PHP_EOL);
        }
        elseif ((float)phpversion() < 7){
            exit("PHP Version must 7.*.*" . PHP_EOL);
        }else{
            // nothing
        }
    }

    /**
     * 
     * Set URL dan parsing parameter
     * @return string URL yang sudah diset
     * 
     */
    function setURL(){
        if(isset($this->parameter)) {
            if (is_array($this->parameter)) {
                return $this->url . "?" . urldecode(http_build_query($this->parameter));
            }else{
                return $this->url . "?" . $this->parameter;
            }
        } else {
            return $this->url;
        }
    }

    /**
     * 
     * @param string $headers Respon headers dari cURL
     * 
     */
    protected function parseCookie($headers){
        $output = [];
        if (stripos(($headers), "Set-Cookie:")) {
            if (preg_match_all("/Set-Cookie: (.*?);(.*?)\r\n/", ($headers), $cookies)) {
                /*if (isset($cookies[1])) {
                    foreach ($cookies[1] as $x => $val) {
                        list($key, $value) = explode("=", $val);
                        $output[$key] = $value;
                    }
                    $this->get = $output;
                }*/
                $this->get = $cookies[1];
            }
            elseif(preg_match_all("/Set-Cookie: (.*?)\r\n/", ($headers), $cookies)){
                $this->get = $cookies[1];
            }
            else{
                $this->get = null;
            }
        }
        else{
            $this->get = null;
        }
        return $this;
    }

    /**
     * 
     * @param string $response Respon headers dari cURL
     * 
     */
    protected function parseHeaders($response){
        $headers = array();
    
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    
        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0){
                $headers['http_code_status'] = $line;
                list(,$code, $status) = explode(' ', $line, 3);
                $headers["http_code"] = $code;
            }
            else{
                list ($key, $value) = explode(': ', $line);
    
                $headers[$key] = $value;
            }
        unset($headers["Set-Cookie"], $headers["set-cookie"]);
        $this->get = $headers;
        return $this;
    }

    /**
     * 
     * @return string Body dari cURL
     * 
     */
    function getBody(){
        return (($this->get["success"]) ? $this->get["response"]["body"] : FALSE);
    }

    /**
     * 
     * @example
     * 
     * $httpRequest = new \HttpRequest\Main;
     * $httpRequest->isGET();
     * $httpRequest->url = "example.com";
     * $httpRequest->execute();
     * echo $httpRequest->getHeaders("Server");
     * 
     * Maka outputnya : ECS (sjc/4E67)
     * 
     * @return string|bool Nilai headers dari headers yang sudah diparsing menjadi array, jika index yang dipanggil tidak ada maka akan return (boolean) false
     * @see self::parseHeaders()
     * 
     */
    function getHeaders($key = null){
        return (($this->get["success"]) ? ((isset($key) && array_key_exists($key, $this->get["response"]["parsed_headers"])) ? $this->get["response"]["parsed_headers"][$key] : FALSE/*$this->get["response"]["parsed_headers"]*/) : FALSE);
    }

    /**
     * @return string Respon headers sesungguhnya yang tidak diparse menjadi array
     */
    function getRealHeaders(){
        return (($this->get["success"]) ? $this->get["response"]["headers"] : FALSE);
    }

    /**
     * @return array Cookie yang sudah diparse menjadi array
     * @see self::parseCookie
     */
    function getCookies(){
        return (($this->get["success"]) ? $this->get["response"]["parsed_cookies"] : FALSE);
    }

    /**
     * @return string Http code dari cURL
     * @see self::parseHeaders()
     */
    function getHttpCode(){
        return (($this->get["success"]) ? $this->get["response"]["parsed_headers"]["http_code"] : FALSE);
    }

    /**
     * @param null $PHP_EOL jika ingin mereturn object get dengan baris baru sekaligus
     * @return object get
     */
    function get($PHP_EOL = null){
        return ((isset($PHP_EOL)) ? $this->get.PHP_EOL : $this->get);
    }

    /**
     * @return string Total waktu proses cURL hingga selesai
     */
    function getTotalTime(){
        return (($this->get["success"]) ? $this->get["response"]["total_time"] : FALSE);
    }
	
    /**
     * @return string User-agent secara acak
     */

    function randomAgent(){
        if (!isset($this->headers) || !is_array($this->headers)) {
            return false;
        }
        $this->headers[] = "Uset-Agent: ".\HttpRequest\UAgent::random();
        return $this;
    }

    /**
     * Mendefisinikan object request sebagai GET
     */
    function isGET(){
        $this->request = "GET";
    }

    /**
     * Mendefisinikan object request sebagai POST
     */
    function isPOST(){
        $this->request = "POST";
    }

    /**
     * @method execute
     * Method ini adalah inti dari class ini, yang akan mengeksekusi cURL beserta konfigurasi lain yang sudah diset
     * 
     * @return object get
     */
    function execute(){
        if (!isset($this->url)) {
            $this->get = [
                "success" => false
            ];
            return $this;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->setURL());
        if (isset($this->proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            if (isset($this->proxy_auth)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth);
            }
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (isset($this->request)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->request);
            if (isset($this->postdata) && strtolower($this->request) == "post") {
                $query = $this->postdata;
                if (is_array($this->postdata)) {
                    $this->postdata = urldecode(http_build_query($query));
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postdata);
                curl_setopt($ch, CURLOPT_POST, 1);
            }elseif (strtolower($this->request) == "post"){
                curl_setopt($ch, CURLOPT_POST, 1);
            }else{
                // nothing
            }
        }

        curl_setopt($ch, CURLOPT_HEADER, 1);

        if(isset($this->headers)){
            $headers = $this->headers;
        }
        else{
            $headers = [
                "Uset-Agent: ".\HttpRequest\UAgent::random()
            ];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $this->get = [
                "success" => false,
                "error_msg" => curl_error($ch)
            ];
            return $this;
        }

        $headers = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        
        curl_close ($ch);
        $this->get = [
            "success" => true,
            "response" => [
                "total_time" => floor($info["total_time"] % 60)."s",
                "body" => $body,
                "headers" => $headers,
                "parsed_headers" => $this->parseHeaders($headers)->get(),
                "parsed_cookies" => $this->parseCookie($headers)->get(),
            ]
        ];
        return $this;
    }
}
?>
