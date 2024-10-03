<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Shop;

use Planet\InterviewChallenge\App;
use Smarty\Exception;

require_once(__DIR__ . '/CartItem.php');

class Cart
{
    private array $items = [];

    public function __construct()
    {
        $params = json_decode($_GET['items'] ?? '[]', true);

        foreach ($params as $item) {
            $price = (int)$item['price'];
            $expires = $item['expires'];

            $mode = $this->valueToMode($expires);
            $modifier = $this->valueToModifier($expires);

            $this->addItem(new CartItem($price, $mode, $modifier));
        }
    }

    /**
     * Convert the value to the corresponding time modifier.
     *
     * @param string $value
     * @return int|null
     */
    private function valueToModifier(string $value): ?int
    {
        return (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT) ?: 0;
    }

    /**
     * Convert the value to the corresponding time mode.
     *
     * @param string $value
     * @return int
     */
    private function valueToMode(string $value): int
    {
        $nonNumericValue = preg_replace('/\d+/', '', $value);
        return match (trim($nonNumericValue)) {
            'hour' => CartItem::MODE_HOUR,
            'min' => CartItem::MODE_MINUTE,
            'second' => CartItem::MODE_SECONDS,
            default => CartItem::MODE_NO_LIMIT,
        };
    }

    /**
     * @param CartItem $cartItem
     * @return bool
     */
    public function addItem(CartItem $cartItem): bool
    {
        $cartItem->isAvailable();
        $this->items[] = $cartItem;

        return true;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @throws Exception
     */
    public function display(): string
    {
        App::smarty()->assign('items', $this->items);
        return App::smarty()->fetch('shop/Cart.tpl');
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        $states = array_map(fn($item) => $item->getState(), $this->items);

        return '[' . implode(',', $states) . ']';
    }
}
