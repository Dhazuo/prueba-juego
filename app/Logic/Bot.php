<?php

namespace App\Logic;

class Bot {
    public function ability($abilities)
    {
        return $abilities[rand(0, 3)];
    }
}
