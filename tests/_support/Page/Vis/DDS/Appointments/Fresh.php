<?php
namespace Page\Vis\DDS\Appointments;

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;
use \Fragment\TakeInWorkAppointmentForm;

use \Codeception\Util\Locator;

/**
 * ДДС / Поручения / Новые
 */
class Fresh extends \Page\BasePage
{
    public static $URL = "/#/vis/61622dc9-504e-0b23-6947-462d808069ac/6a270622-35e1-89a5-1b18-9c8b5c536be2";

    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $newGrid          = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Поручения новые')]]";
    public static $incidentsGrid    = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Инциденты')]]";

    public function openTakeInWorkForm($id) {
        $I = $this->tester;

        $I->executeInSelenium(function(RemoteWebDriver $wd) use ($id) {
            $grid = $wd->findElement( By::xpath(static::$newGrid) );
            $editBtn = $wd->findElement(
                By::xpath(
                    "//tr[contains(@class, 'grid-row') and .//div[contains(@class, 'grid-cell-content') and contains(., '$id')]]//i[contains(@class, 'icon-edit')]"
                )
            );
            $editBtn->click();
        });

        $form = new TakeInWorkAppointmentForm($I);
        return $form;
    }

    public function selectRow($id) {
        $I = $this->tester;
        $I->click(
            "//tr[contains(@class, 'grid-row') and .//div[contains(@class, 'grid-cell-content') and contains(., '$id')]]"
        );
    }

    public function validate() {
        $I = $this->tester;
        $I->waitForElementVisible(static::$newGrid, 10);
    }
}
