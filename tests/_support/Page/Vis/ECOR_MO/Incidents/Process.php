<?php
namespace Page\Vis\ECOR_MO\Incidents;

use \Codeception\Util\Locator;

/**
 * * ВИС / ЕЦОР МО / Инциденты / Инциденты в работе
 */
class Process extends \Page\BasePage {
    public static $URL = "/#/vis/eb3e64e5-dba5-516c-82c3-7755cd857009/f9aa9aec-5739-2393-c031-040cee31f99a";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $grid         = ".grid";
    public static $dataRow      = ".grid-row.data";

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$dataRow, 15);
    }

    /**
     * Выбрать инцидент кликом
     * 
     * @param string $id ID инцидента
     */
    public function selectRow($id) {
        $I = $this->tester;
        $I->wClick(Locator::contains(".grid-cell-content", $id));
    }

    public function openAppointmentForm() {
        $I = $this->tester;
        $I->click(Locator::contains(".btn", "Добавить"));
        $form = new \Fragment\CreateAppointmentForm($I);
        $I->waitForElementVisible($form::$panel, 10);
        return $form;
    }

    public function completeIncident($id) {
        $I = $this->tester;
        $cell = Locator::contains(".grid-cell-content", $id);
        $I->click("//tr[(.//$cell)]//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-InfoCardTakeInWork", 10);
        $I->wClick("#takeInWork");
        $I->waitForElementVisible("#kitform-finishConfirm");
        $I->wClick("#ok");
        $I->waitForElementNotVisible("#kitform-finishConfirm", 10);
        $I->waitForElementNotVisible("#kitform-InfoCardTakeInWork", 10);
        $I->dontSeeElement($cell);
    }
}
