<?php
namespace Page;

use \Codeception\Util\Locator;

use \Page\BasePage;
use \Fragment\Settings\VisFormPanel;

class Settings extends BasePage
{
    public static $URL = "/#/vis/513e782d-d13d-ab72-7d04-3a4e6a81f954/bc9bca93-0935-2c0e-581e-50c9ed6d5188";

    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    /**
     * @var \Fragment\Settings\VisFormPanel;
     */
    public $visFormPanel;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $grid     = ".grid";
    public static $gridRow  = ".grid tr.grid-row.data";

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$gridRow, 10);
    }

    public function openEditFormFor($option) {
        $I = $this->tester;
        $optionTD = Locator::contains("td.grid-cell", $option);
        $sEditBtn = "//tr[(.//$optionTD)]//i[contains( concat(' ', @class, ' '), ' vis-icon-grid-edit ' )]";
        $I->click($sEditBtn);

        $visFormPanel = new VisFormPanel($I);
        $I->waitForElementVisible($visFormPanel::$panel, 10);
        $I->waitForElementVisible($visFormPanel::$form, 10);

        $this->visFormPanel = $visFormPanel;
        return $visFormPanel;
    }

    public function dontSeeEditForm() {
        $I = $this->tester;
        $I->waitForElementNotVisible($this->visFormPanel::$panel, 10);
        $I->dontSeeElementInDOM($this->visFormPanel::$form);
    }
}
