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


    public function __construct(array $options = array())
    {
        if (!$options) {
            parent::__construct([
                'apiKey' => env('API_KEY_BITFLYER'),
                'secret' => env('API_SECRET_BITFLYER'),
            ]);
        } else {
            parent::__construct($options);
        }
    }

    public function getHealth () {
        return $this->publicGetHealth ();
    }

}