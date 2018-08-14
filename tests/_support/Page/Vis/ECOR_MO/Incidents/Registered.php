<?php
namespace Page\Vis\ECOR_MO\Incidents;

use \Codeception\Util\Locator;
use \Component\Select2;

/**
 * ВИС / ЕЦОР МО / Инциденты / Инциденты новые
 */
class Registered extends \Page\BasePage {
    public static $URL = "/#/vis/eb3e64e5-dba5-516c-82c3-7755cd857009/0ce392c7-485e-15f2-d1d3-672f8b463884";

    /** @var \AcceptanceTester */
    protected $tester;

    public $orgSelect;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
        $this->orgSelect = new Select2($I, "#kitform-selectOrganization .select2-container");
    }

    public static $grid     = ".grid";
    public static $dataRow  = ".grid-row.data";

    public static $infoCard         = "#kitform-InfoCardTakeInWork";
    public static $takeInWorkButton = "#kitform-InfoCardTakeInWork #takeInWork";

    public static $incidentMovePopup = "#kitform-IncidentMove";

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

    public function transfer($id, $destination) {
        $I = $this->tester;
        $I->click($this->rowWithId($id)."//i[contains(concat(' ', @class, ' '), ' icon-exchange ')]");
        $I->waitForElementVisible(static::$incidentMovePopup, 10);
        $I->click(
            Locator::contains(
                static::$incidentMovePopup." .buttons button",
                $destination
        ));
        $I->waitForElementVisible("#kitform-selectOrganization", 10);
        $this->orgSelect->expand()->select("ДДС 01 г. Улан-Удэ");
        $I->fillField("#kitform-selectOrganization textarea[name='reasonMove']", "foo bar baz");
        $I->click("#kitform-selectOrganization #ok");
        $I->waitForElementVisible("#kitform-confirmMove", 10);
        $I->click("#kitform-confirmMove #yes");
        $I->waitForElementNotVisible($this->rowWithId($id), 10);
    }

    private function rowWithId($id) {
        $cell = Locator::contains(".grid-cell-content", $id);
        return "//tr[(.//$cell)]";
    }
}