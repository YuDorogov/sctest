<?php
namespace Page\Vis\ECOR_MO;

use \Codeception\Util\Locator;
use \Fragment\Vis\ECOR_MO\Map\Facets;
use \Fragment\Vis\ECOR_MO\Map\RightPanel;
use \Fragment\Settings\VisFormPanel;

class Map extends \Page\BasePage {
    public static $URL = "/#/vis/eb3e64e5-dba5-516c-82c3-7755cd857009/00d6c393-a23e-6e38-49a8-97489f723ff1";

    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    /**
     * @var \Fragment\ECOR_MO\Map\Facets
     */
    protected $facets;

    /**
     * @var \Fragment\ECOR_MO\Map\RightPanel
     */
    protected $rightPanel;


    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $map = "#map";

    public function openCreateIncidentForm() {
        $I = $this->tester;
        $panel = $this->rightPanel();

        $I->click($panel::$createIncident);
        $I->waitForElementVisible($panel::$card, 15);

        return $panel;
    }

    public function rightPanel() {
        if ($this->rightPanel === null) {
            $this->rightPanel = new RightPanel($this->tester);
        }
        return $this->rightPanel;
    }

    public function facets() {
        if ($this->facets === null) {
            $this->facets = new Facets($this->tester);
        }
        return $this->facets;
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$map, 15);
    }
}
