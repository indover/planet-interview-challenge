<?php

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Shop\CartItem;

class CartItemTest extends TestCase
{
    protected CartItem $object;

    protected function setUp(): void
    {
        $this->object = new CartItem(123, CartItem::MODE_NO_LIMIT);
    }

    public function testIsAvailableWhenNoLimit(): void
    {
        $this->assertTrue($this->object->isAvailable());
    }

    public function testIsAvailableWithExpiry(): void
    {
        $this->object = new CartItem(123, CartItem::MODE_NO_LIMIT, 1);
        $this->assertTrue($this->object->isAvailable());

        $this->object = new CartItem(123, CartItem::MODE_SECONDS, 60);
        $this->assertFalse($this->object->isAvailable());

        sleep(30);
        $this->assertFalse($this->object->isAvailable());

        sleep(30);
        $this->assertTrue($this->object->isAvailable());
    }
}
