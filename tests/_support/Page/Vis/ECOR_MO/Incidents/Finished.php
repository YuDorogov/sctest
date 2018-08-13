<?php
namespace Page\Vis\ECOR_MO\Incidents;

use \Codeception\Util\Locator;

/**
 * * ВИС / ЕЦОР МО / Инциденты / Инциденты завершённые
 */
class Finished extends \Page\BasePage {
    public static $URL = "/#/vis/eb3e64e5-dba5-516c-82c3-7755cd857009/9219876a-b7ba-1052-a5a2-4689883588c9";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $dataRow  = ".grid-row.data";
    public static $infoCard = "#kitform-InfoCardTakeInWork";

    public function closeIncident($id) {
        $I = $this->tester;
        $cell = Locator::contains(".grid-cell-content", $id);
        $I->click("//tr[(.//$cell)]//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible(static::$infoCard, 10);

        $I->wClick("#takeInWork");
        
        $I->waitForElementNotVisible(static::$infoCard, 10);
        $I->dontSeeElement($cell);
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$dataRow, 15);
    }
}
