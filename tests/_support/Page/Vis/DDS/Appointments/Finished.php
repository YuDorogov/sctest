<?php
namespace Page\Vis\DDS\Appointments;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ДДС / Поручения / Завершённые
 */
class Finished extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/dd81f9b5-d369-c7a7-76b1-4fe9b87f74c3";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $appointmentsGrid = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Поручения завершенные')]]";

    /**
     * Завершить поручение с `Работы проведены`
     */
    public function close($id) {
        $I = $this->tester;
        $cell = Locator::contains(".grid-cell-content", $id);
        $I->click("//tr[(.//$cell)]//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-OrderCloseWork", 10);
        $I->wClick("#close");
        $I->waitForElementNotVisible("#kitform-OrderCloseWork", 10);
        $I->dontSeeElement($cell);
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".vis-grid");
    }
}
