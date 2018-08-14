<?php

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;
use \Codeception\Util\Locator;

use \Page\Vis\ECOR_MO\Map as MapPage;
use \Page\Vis\ECOR_MO\Incidents\Registered;
use \Page\Vis\DDS\Incidents\Fresh;
use \Page\Vis\DDS\Incidents\InWork;
use \Page\Vis\DDS\Incidents\Finished;

class DDSCest
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
        sleep(10);
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
    public function testTransferToDDS(
        AcceptanceTester $I,
        Fresh $DDSNewIncidentsPage,
        Registered $regIncidentPage,
        InWork $DDSInWorkIncidentsPage,
        Finished $DDSFinishedIncidentsPage
    ) {
        $regIncidentPage->navigate();
        $regIncidentPage->transfer($this->incident->id, "ДДС 01");
        sleep(5);
        $DDSNewIncidentsPage->navigate();
        $DDSNewIncidentsPage->takeInWork($this->incident->id);
        sleep(5);
        $DDSInWorkIncidentsPage->navigate();
        $DDSInWorkIncidentsPage->completeIncident($this->incident->id);
        sleep(5);
        $DDSFinishedIncidentsPage->navigate();
        $DDSFinishedIncidentsPage->closeIncident($this->incident->id);
    }
}
