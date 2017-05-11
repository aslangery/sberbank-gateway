<?php
/**
 * Author: Andrey Morozov
 * Email: andrey@3davinci.ru
 * Date: 02.05.2017
 */

namespace Sberbank\Tests;

use Sberbank\RestGateway;

class RestGatewayTest extends SberbankTestCase
{
    /**
     * @var RestGateway
     */
    public $gateway;

    private $globalParams = [
        'password' => 'password',
        'userName' => 'username',
        'testMode' => true,
    ];

    /**
     * @param string $mock
     */
    private function setGateway($mock)
    {
        $this->gateway = new RestGateway(
            $this->globalParams,
            $this->setMockSberbankClient($mock)
        );
    }

    public function testRegisterOrderRequestSuccess()
    {
        $this->setGateway('RegisterOrderSuccess');

        /** @var \Sberbank\Message\RegisterOrderRequest $request */
        $request = $this->gateway->registerOrder([
            'orderNumber' => 1,
            'amount' => 12000,
            'returnUrl' => 'https://server/applicaton_context/finish.html'
        ]);

        $request->validate();

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();

        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertTrue($response->isSuccessful());
    }

    public function testRegisterOrderRequestError()
    {
        $this->setGateway('RegisterOrderError');
        /** @var \Sberbank\Message\RegisterOrderRequest $request */
        $request = $this->gateway->registerOrder([
            'orderNumber' => 1,
            'returnUrl' => 'https://server/applicaton_context/finish.html'
        ]);

        $this->expectException(\Sberbank\Exception\InvalidRequestException::class);
        $request->validate();

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();

        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertFalse($response->isSuccessful());
    }

    public function testOrderStatusSuccess()
    {
        $this->setGateway('OrderStatusSuccess');
        /** @var \Sberbank\Message\OrderStatusRequest $request */
        $request = $this->gateway->orderStatus([
            'orderId' => 'b8d70aa7-bfb3-4f94-b7bb-aec7273e1fce'
        ]);

        $request->validate();

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();

        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertTrue($response->isSuccessful());
    }

    public function testOrderStatusError()
    {
        $this->setGateway('OrderStatusError');
        /** @var \Sberbank\Message\OrderStatusRequest $request */
        $request = $this->gateway->orderStatus();

        $this->expectException(\Sberbank\Exception\InvalidRequestException::class);
        $request->validate();

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();

        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertFalse($response->isSuccessful());
    }

    public function testBindingsSuccess()
    {
        $this->setGateway('BindingsSuccess');
        /** @var \Sberbank\Message\BindingsRequest $request */
        $request = $this->gateway->getBindings('22');
        $request->validate();

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();


        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertTrue($response->isSuccessful());
    }

    public function testBindingsError()
    {
        $this->setGateway('BindingsError');
        /** @var \Sberbank\Message\BindingsRequest $request */
        $request = $this->gateway->getBindings('13');

        /** @var \Sberbank\Message\RestResponse $response */
        $response = $request->send();

        $this->assertInstanceOf('\Sberbank\Message\RestResponse', $response);
        $this->assertNotEmpty($response->getData());
        $this->assertFalse($response->isSuccessful());
    }
}