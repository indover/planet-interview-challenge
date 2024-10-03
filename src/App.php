<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge;

use Smarty\Exception;
use Smarty\Smarty;
use Planet\InterviewChallenge\Shop\Cart;

class App
{
    public static Smarty $smarty;

    public static function smarty(): Smarty
    {
        return self::$smarty;
    }

    /**
     * @throws Exception
     */
    public static function run(): void
    {
        // init
        self::$smarty = new Smarty();
        self::$smarty->setTemplateDir([__DIR__, __DIR__ . '/tpl']);
        self::$smarty->setConfigDir(__DIR__ . '/config');
        self::$smarty->setCompileDir(__DIR__ . '/../tmp/templates_c');
        self::$smarty->setCacheDir(__DIR__ . '/../tmp/cache');

        self::$smarty->registerPlugin('modifier', 'format_date', function ($timestamp, $format = 'Y-m-d') {
            return date($format, $timestamp);
        });

        // run
        ob_start();
        self::$smarty->assign('ShopCart', new Cart());
        self::$smarty->display('App.tpl');
        $render = ob_get_contents();
        ob_end_clean();
        print $render;
    }
}
