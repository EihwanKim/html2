<?php
// app/Library/BaseClass.php
namespace App\Library;

class Utils
{
    public static function getMasterCoins() {
        return explode(',', env('TARGET_COINS'));
    }
}