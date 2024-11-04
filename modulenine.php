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
        // Methods are more times deprecated, but not removed.
        // So, use a newer one
        $temp = new EmployeeSession();
    }

    protected function prestashop17()
    {
        // Methods are more times deprecated, but not removed, again.
        // So, use a newer one
        // @phpstan-ignore class.notFound
        $temp = new CustomerSession();
    }

    protected function prestashop8()
    {
        $temp = Carrier::getCarrierNameFromShopName();
    }

    protected function prestashop9()
    {
        $temp = new Order(1);
        $temp->getDiscounts();
    }
}
