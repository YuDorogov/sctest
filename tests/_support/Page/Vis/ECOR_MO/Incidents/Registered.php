<?php
namespace Page\Vis\ECOR_MO\Incidents;

use \Codeception\Util\Locator;

/**
 * ВИС / ЕЦОР МО / Инциденты / Инциденты новые
 */
class Registered extends \Page\BasePage {
    public static $URL = "/#/vis/eb3e64e5-dba5-516c-82c3-7755cd857009/0ce392c7-485e-15f2-d1d3-672f8b463884";

    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $grid     = ".grid";
    public static $dataRow  = ".grid-row.data";

    public static $infoCard         = "#kitform-InfoCardTakeInWork";
    public static $takeInWorkButton = "#kitform-InfoCardTakeInWork #takeInWork";

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$dataRow, 15);
    }

    public function takeInWork($id) {
        $I = $this->tester;

        $I->click(
            $this->rowWithId($id)."//i[contains(concat(' ', @class, ' '), ' icon-edit ')]"
        );
        $I->waitForElementVisible(static::$infoCard, 10);

        $I->click(static::$takeInWorkButton);
        $I->waitForElementNotVisible(static::$infoCard, 10);
        $I->dontSeeElementInDOM(static::$infoCard);
        $I->dontSeeElement($this->rowWithId($id));
    }

    private function rowWithId($id) {
        $cell = Locator::contains(".grid-cell-content", $id);
        return "//tr[(.//$cell)]";
    }
}