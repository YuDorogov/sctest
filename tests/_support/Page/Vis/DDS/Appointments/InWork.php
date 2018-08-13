<?php
namespace Page\Vis\DDS\Appointments;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;

use \Codeception\Util\Locator;

/**
 * ДДС / Поручения / В работе
 */
class InWork extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/8ed261ef-15bd-363d-45f1-f1b7aee0527c";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $appointmentsGrid = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Поручения в работе')]]";

    public function openAppointmentCard($id) {
        $I = $this->tester;
        $cell = Locator::contains(".grid-cell-content", $id);
        $I->click("//tr[(.//$cell)]//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-OrderTakeInWork", 10);
    }

    /**
     * Завершить поручение
     */
    public function complete($id, $isWorkComplete = true) {
        $I = $this->tester;
        if($isWorkComplete) {
            $I->click("#completed");
        } else {
            $I->click("#nwcompleted");
        }
        $I->waitForElementNotVisible("#kitform-OrderTakeInWork", 10);
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(".vis-grid");
    }
}
