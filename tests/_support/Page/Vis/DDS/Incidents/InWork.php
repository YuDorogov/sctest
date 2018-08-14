<?php

namespace Page\Vis\DDS\Incidents;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ДДС / Инциденты / В работе
 */
class InWork extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/d740a232-ce4e-69b1-9ad1-2db250d0ce10";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public function completeIncident($id) {
        $I = $this->tester;
        $cell = Locator::contains(".grid-cell-content", $id);
        $I->click("//tr[(.//$cell)]//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-InfoCardTakeInWork", 10);
        $I->wClick("#takeInWork");
        $I->waitForElementNotVisible("#kitform-InfoCardTakeInWork", 10);
        $I->dontSeeElement($cell);
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".grid-row.data", 10);
    }
}
