<?php
/**
 * Author: Andrey Morozov
 * Email: andrey@3davinci.ru
 * Date: 02.05.2017
 */

namespace Sberbank;

use Http\Client\HttpClient;
use Sberbank\Http\Client;
use Sberbank\Message\RequestAbstract;

class RestGateway implements GatewayInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var array
     */
    private $parameters;

    /**
     * RestGateway constructor.
     * @param array $parameters
     * @param HttpClient|null $client
     */
    public function __construct(array $parameters, $client = null)
    {
        $this->httpClient = $client ?: new Client();
        $this->parameters = array_replace($this->getDefaultParameters(), $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestAbstract
     */
    public function orderStatus(array $parameters = [])
    {
        return $this->createRequest('OrderStatus', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestAbstract
     */
    public function registerOrder(array $parameters = [])
    {
        return $this->createRequest('RegisterOrder', $parameters);
    }

    /**
     * @return array
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getDefaultParameters() : array
    {
        return [
            'password' => '',
            'userName' => '',
            'testMode' => false,
        ];
    }

    /**
     * @param string $classNmae
     * @param array $parameters
     * @return RequestAbstract
     */
    private function createRequest(string $classNmae, array $parameters) : RequestAbstract
    {
        $classRequest = '\Sberbank\Message\\'.$classNmae.'Request';
        $classResponse = '\Sberbank\Message\\'.$classNmae.'Response';
        /** @var \Sberbank\Message\RequestAbstract $requestObj */
        $requestObj = new $classRequest($this->httpClient, $classResponse);

        return $requestObj->initialize(array_replace($this->getParameters(), $parameters));
    }
}