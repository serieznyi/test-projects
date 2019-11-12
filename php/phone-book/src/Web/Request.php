<?php

namespace App\Web;

class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $files;

    /**
     * Request constructor.
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param $params
     * @param $files
     */
    public function __construct($method, $uri, $data, $params, $files)
    {
        $this->method = strtolower($method);
        $this->data = $data;
        $this->uri = $uri;
        $this->params = $params;
        $this->files = $files;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function isPost()
    {
        return $this->method === 'post';
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $key
     * @return string|integer
     */
    public function getParam($key)
    {
        return $this->params[$key];
    }
}