<?php

namespace Page\Vis\DDS\Incidents;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ДДС / Инциденты / Новые
 */
class Fresh extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/002e0393-947a-c9dd-b484-4547a4bef677";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $infoCard         = "#kitform-InfoCardTakeInWork";
    public static $takeInWorkButton = "#kitform-InfoCardTakeInWork #takeInWork";

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

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".vis-grid");
    }

    private function rowWithId($id) {
        $cell = Locator::contains(".grid-cell-content", $id);
        return "//tr[(.//$cell)]";
    }
}
