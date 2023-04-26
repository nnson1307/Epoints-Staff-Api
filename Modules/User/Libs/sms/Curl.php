<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/25/2019
 * Time: 6:28 PM
 */

namespace Modules\User\Libs\sms;

use Modules\User\Libs\sms\UrlDecode;

class Curl
{
    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';

    protected $ch       = null;
    protected $timeout  = 90;
    protected $header   = array();
    protected $cookie   = array('use' => false, 'path' => '');
    protected $ssl      = array('use' => false, 'path' => '');
    protected $disSSL   = false;
    protected $method   = self::METHOD_POST;
    protected $dataPost = array();
    protected $followlo = true;
    protected $headerRes= null;
    protected $disHeaderRes = true;

    protected $defaultHeader = array(
        'User-Agent'   => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31',
        'Accept'       => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Connection'   => 'Keep-Alive',
        'Content-type' => 'application/x-www-form-urlencoded'
    );


    public function addHeader($name, $value)
    {
        $this->header[$name] = $value;
        return $this;
    }

    public function setHeaders(array $header)
    {
        $this->header = array_merge($this->header, $header);
        return $this;
    }

    public function disHeaderRes()
    {
        return $this->disHeaderRes = false;
    }

    public function setCookie($path)
    {
        if ($path !== false) {
            $this->cookie['use']  = true;
            $this->cookie['path'] = $path;
        }
        else {
            $this->cookie['use']  = false;
        }
        return $this;
    }


    public function setSsl($path)
    {
        if ($path !== false) {
            $this->ssl['use']  = true;
            $this->ssl['path'] = $path;
        }
        else {
            $this->ssl['use']  = false;
        }
        return $this;
    }


    public function disableSSL()
    {
        $this->disSSL = true;
        return $this;
    }


    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }


    public function setPostParams($params)
    {
        $this->dataPost = $params;
        return $this;
    }

    protected function setHeaderRes($ch, $response)
    {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);

        return $this->headerRes = $header;
    }

    public function execute($url)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->getHeader());
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $this->followlo);

        if(!$this->disHeaderRes)
        {
            curl_setopt($this->ch, CURLOPT_HEADER, true);
        }

        if ($this->cookie['use'])
        {
            curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookie['path']);
            curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookie['path']);
        }

        if ($this->ssl['use'])
        {
            curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt ($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt ($this->ch, CURLOPT_CAINFO, $this->ssl['path']);
        }

        if  ($this->disSSL)
        {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        if ($this->method == self::METHOD_POST) {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, is_array($this->dataPost) ? UrlDecode::paramsArray($this->dataPost) : $this->dataPost);
        }

        $response = curl_exec($this->ch);

        if(!$this->disHeaderRes)
        {
            if($response)
                $this->setHeaderRes($this->ch, $response);
        }

        return $response;
    }


    public function getErrorCode()
    {
        return curl_errno($this->ch);
    }


    public function getError()
    {
        return curl_error($this->ch);
    }


    public function close()
    {
        //curl_close($this->ch);
    }


    public function __destruct()
    {
        $this->close();
    }


    protected function getHeader()
    {
        $this->header = array_merge($this->defaultHeader, $this->header);
        $arrHeader    = array();

        foreach ($this->header as $name => $val) {
            $arrHeader[] = sprintf('%s: %s', $name, $val);
        }

        return $arrHeader;
    }

    public function getHeaderResponse()
    {
        return $this->headerRes;
    }
}