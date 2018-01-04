<?php
/**
 * Created by IntelliJ IDEA.
 * User: u-kimu
 * Date: 2018/01/04
 * Time: 12:32
 */

namespace App\Library;

use ccxt\bitflyer;

class MyBitflyer extends bitflyer
{
    public function getHealth () {
        return $this->publicGetHealth ();
    }

}