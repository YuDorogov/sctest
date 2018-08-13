<?php

namespace Fragment\Vis\ECOR_MO\Map;

use \Codeception\Util\Locator;
use \Fragment\CreateIncidentForm;

class RightPanel {
    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    protected $createIncidentForm;

    public static $createIncident       = "#r-panel .controls button.create-incident";
    public static $incidentList         = "#r-panel .content";
    public static $incidentElement      = "#r-panel .content .incident";
    public static $incidentElementName  = "#r-panel .content .incident > span.name";


    public function createIncidentForm() {
        return $this->createIncidentForm;
    }

    public function openCreateIncidentForm() {
        $I = $this->tester;

        $I->click(static::$createIncident);
        $form = new CreateIncidentForm($I);
        $I->waitForElementVisible($form::$card, 15);

        return $form;
    }

    /**
     * Открыть инфокарту инцидента
     * 
     * @param string $id (optional) ID инидента вида `i-19990102-101112`
     * 
     * @return \Fragment\Vis\ECOR_MO\Map\IncidentCardView 
     */
    public function openIncidentCardView($id) {
        $I = $this->tester;
        // $I->click(Locator::elementAt(static::$incidentElement, 1)."//i[@class='vis-icon-grid-review']");
        $I->click(Locator::contains(static::$incidentElement, $id)."//i[@class='vis-icon-grid-review']");
        $I->waitForElementVisible("#kitform-Marker-popup", 5);
        return new IncidentCardView($I);
    }

    /**
     * @param int $index Индекс с 1
     */
    public function incidentIdAt($index) {
        $I = $this->tester;
        $text = $I->grabTextFrom(
            Locator::elementAt(static::$incidentElement, $index)."//span[contains(concat(' ', @class, ' '), ' name ')]"
        );
        
        $pattern = "/(i-\d{8}-\d{6})/";
        preg_match($pattern, $text, $matches);
        return $matches[1];
    }
}
