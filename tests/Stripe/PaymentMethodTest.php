<?php

namespace Stripe;

class PaymentMethodTest extends TestCase
{
    const TEST_RESOURCE_ID = 'pm_123';

    public function testIsListable()
    {
        $this->expectsRequest(
            'get',
            '/v1/payment_methods'
        );
        $resources = PaymentMethod::all();
        $this->assertTrue(is_array($resources->data));
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resources->data[0]);
    }

    public function testIsRetrievable()
    {
        $this->expectsRequest(
            'get',
            '/v1/payment_methods/' . self::TEST_RESOURCE_ID
        );
        $resource = PaymentMethod::retrieve(self::TEST_RESOURCE_ID);
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
    }

    public function testIsCreatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/payment_methods'
        );
        $resource = PaymentMethod::create([
            "customer" => "cus_123"
        ]);
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
    }

    public function testIsSaveable()
    {
        $resource = PaymentMethod::retrieve(self::TEST_RESOURCE_ID);
        $resource->metadata["key"] = "value";
        $this->expectsRequest(
            'post',
            '/v1/payment_methods/' . $resource->id
        );
        $resource->save();
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
    }

    public function testIsUpdatable()
    {
        $this->expectsRequest(
            'post',
            '/v1/payment_methods/' . self::TEST_RESOURCE_ID
        );
        $resource = PaymentMethod::update(self::TEST_RESOURCE_ID, [
            "metadata" => ["key" => "value"],
        ]);
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
    }

    public function testIsDeletable()
    {
        $resource = PaymentMethod::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'delete',
            '/v1/payment_methods/' . self::TEST_RESOURCE_ID
        );
        $resource->delete();
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
    }

    public function testCanAttach()
    {
        $resource = PaymentMethod::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'post',
            '/v1/payment_methods/' . $resource->id . '/attach'
        );
        $resource = $resource->finalizePaymentMethod();
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
        $this->assertSame($resource, $resource);
    }

    public function testCanDetach()
    {
        $resource = PaymentMethod::retrieve(self::TEST_RESOURCE_ID);
        $this->expectsRequest(
            'post',
            '/v1/payment_methods/' . $resource->id . '/detach'
        );
        $resource = $resource->markUncollectible();
        $this->assertInstanceOf("Stripe\\PaymentMethod", $resource);
        $this->assertSame($resource, $resource);
    }
}
