<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;
use \Codeception\Util\Locator;

use \Page\Vis\ECOR_MO\Map as MapPage;
use \Page\Vis\ECOR_MO\Incidents\Registered;
use \Page\Vis\CUKS\DelegatedIncidents;
use \Page\Vis\ECOR_MO\Incidents\Process;

class CUKSCest
{
    public $incident = null;

    public function _before(AcceptanceTester $I) {
        $I->amOnPage("/");
        try {
            $I->waitForElementNotVisible(".load-app-cache", 10);
            $I->executeJS("document.querySelector('.load-app-cache').remove()");
        } catch(Exception $e) {
            /* do nothing */
        }
        $I->waitForElementVisible(".auth-form-box");
        $I->fillField("#auth-username", "admin");
        $I->fillField("#auth-password", "admin");
        $I->click(".auth-form-box button[type='submit']");
        $I->waitForElementVisible(".http-loading-spinner-container");
    }

    public function _after() {
    }

    public function testIncident_Create(
        Registered $regIncidentPage,
        AcceptanceTester $I,
        MapPage $mapPage
    ) {
        $faker = $faker = Faker\Factory::create();

        $mapPage->navigate();
        $form = $mapPage->rightPanel()->openCreateIncidentForm();
        $form->fillAddressField("Республика Бурятия, г. Улан-Удэ, ул. Ленина, д. 2");
        $form->selectKsipType();
        $form->fillDescriptionField($faker->sentence());
        $form->fillCommentField($faker->sentence());

        $date = $I->grabValueFrom($form::$ksipActualDateField);
        $form->incident->ksipActualDate = $date;
    
        $newIncident = $form->submit();

        $panel = $mapPage->rightPanel();
        $I->waitForElementVisible($panel::$incidentElement, 15);

        $newIncident->id = $panel->incidentIdAt(1);

        $cardView = $panel->openIncidentCardView($newIncident->id);

        $I->assertEquals($I->grabValueFrom($cardView::$incidentType),     $newIncident->ksipType);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentAddress),  $newIncident->address);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentState),    $newIncident->ksipActualDate.":00");
        $I->assertEquals($I->grabValueFrom($cardView::$incidentComment),  $newIncident->comment);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentDescriprion),  $newIncident->ksipDescription);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentOrganisation), $newIncident->organization);

        $this->incident = $newIncident;
    }

    /**
     * @depends testIncident_Create
     */
    public function testTransferToCUKS(
        AcceptanceTester $I,
        Registered $registeredIncidentPage,
        Process $processIncidentPage, 
        DelegatedIncidents $CUKSDelegatedIncidents
    ) {
        $this->incident = new StdClass();
        $this->incident->{"id"} = "i-20180814-103125";
        $registeredIncidentPage->navigate();
        $registeredIncidentPage->takeInWork($this->incident->id);
        sleep(5);
        $processIncidentPage->navigate();
        $processIncidentPage->transferToCUKS($this->incident->id);

        $CUKSDelegatedIncidents->navigate();
        $CUKSDelegatedIncidents->close($this->incident->id);
    }
}
