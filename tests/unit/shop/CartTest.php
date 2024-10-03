<?php

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Shop\Cart;
use Planet\InterviewChallenge\Shop\CartItem;

class CartTest extends TestCase
{
    public function setUp(): void
    {
        $this->object = new Cart();
    }

    public function testGetState()
    {
        $this->object->addItem(new CartItem(123, CartItem::MODE_NO_LIMIT));
        $state = $this->object->getState();

        $expected = json_encode([
            [
                'price' => 123,
                'expires' => CartItem::MODE_NO_LIMIT
            ]
        ]);

        $this->assertCount(1, json_decode($state));
        $this->assertEquals($expected, $state);
    }
}
