<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Shop;

use Smarty\Exception;
use Planet\InterviewChallenge\App;
use RuntimeException;

class CartItem extends RuntimeException
{
    public const int MODE_NO_LIMIT = 0;
    public const int MODE_HOUR = 1;
    public const int MODE_MINUTE = 10;
    public const int MODE_SECONDS = 1000;

    private int $expires;

    private int $price;

    public function __construct(int $price, int $mode, ?int $modifier = null)
    {
        parent::__construct("CartItem Exception");

        $this->price = $price;
        $this->expires = $this->calculateExpires($mode, $modifier);
    }

    /**
     * Calculate the expiration time based on the mode and modifier.
     *
     * @param int $mode
     * @param int|null $modifier
     * @return int
     */
    private function calculateExpires(int $mode, ?int $modifier): int
    {
        return match ($mode) {
            self::MODE_NO_LIMIT => self::MODE_NO_LIMIT,
            self::MODE_HOUR => strtotime('+' . ($modifier ?: self::MODE_HOUR) . ' hour'),
            self::MODE_MINUTE => strtotime('+' . ($modifier ?: self::MODE_MINUTE) . ' minutes'),
            self::MODE_SECONDS => strtotime('+' . ($modifier ?: self::MODE_SECONDS) . ' seconds'),
            default => 0,
        };
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->expires == 0 || $this->expires <= time();
    }

    /**
     * Returns the state representation of the object.
     *
     * @return string State representation of the class.
     */
    public function getState(): string
    {
        return json_encode([
            'price' => $this->price,
            'expires' => $this->expires,
        ]);
    }

    /**
     * @throws Exception
     */
    public function display(): string
    {
        App::smarty()->assign('price', $this->price);
        App::smarty()->assign('expires', $this->expires);

        return App::smarty()->fetch('shop/CartItem.tpl');
    }
}
