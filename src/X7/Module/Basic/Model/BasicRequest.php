<?php

namespace X7\Module\Basic\Model;

use X7\Model\Model;
use X7\Module\Basic\Constant\RequestMethod;

class BasicRequest extends Model
{

    protected $url;

    protected $method = RequestMethod::POST;

    protected $body = [];

    protected $contentType = "application/x-www-form-urlencoded";

    protected $headers = [];


    public function getBody()
    {
        return $this->body;
    }

    public function setBody($data)
    {
        $this->body = $data;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function setHeader($key, $value = null)
    {
        if (is_array($key)) {
            $this->headers = array_merge($this->headers, $key);
        } else {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

}