<?php

class ModuleNine extends Module
{
    public function __construct()
    {
        $this->name = 'modulenine';

        parent::__construct();
    }

    protected function test()
    {
        $order = new Order(1);
        $order->getDiscounts();
    }
}
