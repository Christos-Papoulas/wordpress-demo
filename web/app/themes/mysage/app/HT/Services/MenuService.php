<?php

namespace App\HT\Services;

use Log1x\Navi\Navi;

class MenuService
{
    public static function getTopBar()
    {
        $navigation = (new Navi)->build('topbar_navigation');

        if ($navigation->isEmpty()) {
            return;
        }

        return $navigation->toArray();
    }

    public static function getPrimary()
    {
        $navigation = (new Navi)->build('primary_navigation');
        if ($navigation->isEmpty()) {
            return;
        }

        return $navigation->toArray();
    }

    public static function getBottomFooter()
    {
        $navigation = (new Navi)->build('bottom_footer_navigation');

        if ($navigation->isEmpty()) {
            return;
        }

        return $navigation->toArray();
    }

    public static function getFooterMenu($id)
    {
        $navigation = (new Navi)->build($id);

        if ($navigation->isEmpty()) {
            return;
        }

        return $navigation->toArray();
    }
}
