<?php
namespace Page\Vis\CUKS;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ЦУКС / Делегированные инциденты
 */
class DelegatedIncidents extends \Page\BasePage
{
    public static $URL = "/#/vis/665f9530-156f-8804-8b76-be3d97fcee68/50c4cef9-a324-f29c-e9fd-b45466c02cc2";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $appointmentsGrid = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Поручения завершенные')]]";

    public function close($id) {
        $I = $this->tester;
        $cell = $this->rowWithId($id);
        $I->click("$cell//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-InfoCardTakeInWork", 10);
        $I->wClick("#takeInWork");
        $I->waitForElementNotVisible("#kitform-InfoCardTakeInWork", 10);
        $I->dontSeeElement($cell);
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".grid-row.data", 10);
    }

    private function rowWithId($id) {
        $cell = Locator::contains(".grid-cell-content", $id);
        return "//tr[(.//$cell)]";
    }
}
