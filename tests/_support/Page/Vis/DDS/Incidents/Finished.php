<?php

namespace Page\Vis\DDS\Incidents;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ДДС / Инциденты / Завершенные
 */
class Finished extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/bf4f1a20-340e-aa74-bbaf-cbf362e6f471";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

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
        $I->waitForElementVisible(".grid-row.data", 10);
    }
}
