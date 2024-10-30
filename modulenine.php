<?php

class ModuleNine extends Module
{
    public function __construct()
    {
        $this->name = 'modulenine';

        parent::__construct();
    }

    protected function prestashop16()
    {
        //todo
    }

    protected function prestashop17()
    {
        //todo
    }

    protected function prestashop8()
    {
        if (version_compare(_PS_VERSION_, '8.0.0', '<')) {
            $temp = Carrier::getCarrierNameFromShopName();
        }
    }

    protected function prestashop9()
    {
        $temp = new Order(1);
        $temp->getDiscounts();
    }
}
