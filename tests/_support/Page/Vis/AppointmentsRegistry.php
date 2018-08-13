<?php
namespace Page\Vis;

use \Codeception\Util\Locator;
use \Fragment\Vis\ECOR_MO\Map\Facets;
use \Fragment\Vis\ECOR_MO\Map\RightPanel;
use \Fragment\Settings\VisFormPanel;

class AppointmentsRegistry extends \Page\BasePage {
    public static $URL = "/#/vis/df55659c-882e-6038-25ff-1025df0921bd/26e45b81-1833-0610-d46d-c024ee7e586c";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $grid = ".vis-grid";

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".grid-row.data", 10);
    }
}
