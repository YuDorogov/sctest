<?php

namespace Fragment\Vis\ECOR_MO\Map;

class Facets {
    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $incidents    = "#incidents";
    public static $meteo        = "#meteo";
    public static $objects      = "#objects";
    public static $atm          = "#atm";
    public static $rnis         = "#rnis";
    public static $pmso         = "#pmso";
    public static $cam          = "#cam";
    public static $pvf          = "#pvf";
}
