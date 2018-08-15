<?php

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;
use \Codeception\Util\Locator;

use \Page\Vis\ECOR_MO\Map as MapPage;
use \Page\Vis\ECOR_MO\Incidents\Registered;
use \Page\Vis\ECOR_MO\Incidents\Process;
use \Page\Vis\ECOR_MO\Incidents\Finished as FinishedIncidentsPage;
use \Page\Vis\AppointmentsRegistry;
use \Page\Vis\DDS\Appointments\Fresh as NewAppointmentsPage;
use \Page\Vis\DDS\Appointments\InWork;
use \Page\Vis\DDS\Appointments\Finished;

class CreateIncidentCest
{
    public $incident = null;
    public $appointment = null;

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

    public function testIncident_Create(AcceptanceTester $I, MapPage $mapPage, Registered $regIncidentPage) {
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
    public function testIncident_TakeInWork(
        AcceptanceTester $I,
        Process $processIncidentPage,
        Registered $registeredIncidentPage
    ) {
        $newIncident = $this->incident;
        // $newIncident = new StdClass();
        // $newIncident->{"id"} = "i-20180808-062229";

        $registeredIncidentPage->navigate();
        $registeredIncidentPage->takeInWork($newIncident->id);

        sleep(10);
        $processIncidentPage->navigate();
        $I->waitForText($newIncident->id, 15, ".grid-cell-content");
    }

    /**
     * @depends testIncident_TakeInWork
     */
    public function testAppointment_CreateAndSend(AcceptanceTester $I, Process $processIncidentPage) {
        $newIncident = $this->incident;
        // $newIncident = new StdClass();
        // $newIncident->{"id"} = "i-20180726-095451";

        $faker = $faker = Faker\Factory::create();

        $processIncidentPage->navigate();
        $processIncidentPage->selectRow($newIncident->id);
        $form = $processIncidentPage->openAppointmentForm();
        $form->fillStepNum($faker->randomDigit);
        $form->fillStepDescr($faker->sentence());
        $form->fillStepTime($faker->randomDigitNotNull);
        $form->selectStepType("Поручение 01");
        $appointment = $form->submit();

        $appointmentGrid = "//div[contains(@class, 'vis-builder-element') and .//span[contains(., 'Сценарий реагирования')]]";
        $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $wd) use ($appointmentGrid, $I, $appointment) {
            $row = $wd->findElement(By::xpath($appointmentGrid))->findElement(By::cssSelector(".grid-row.data"));
            $I->assertEquals(
                $row->findElement(By::xpath(".//td[position()=1]"))->getText(),
                $appointment->stepNum
            );
            $I->assertEquals(
                $row->findElement(By::xpath(".//td[position()=2]"))->getText(),
                $appointment->stepDescr
            );
            $I->assertEquals(
                $row->findElement(By::xpath(".//td[position()=3]"))->getText(),
                $appointment->stepTime
            );
        });
        $this->appointment = $appointment;

        // Отправить поручение
        $I->click($appointmentGrid."//i[contains(@class, 'icon-edit')]");
        $I->waitForElementVisible("#kitform-scriptStep", 10);
        $I->click("#order");
        $I->waitForElementNotVisible("#kitform-scriptStep", 10);
    }

    /**
     * @depends testAppointment_CreateAndSend
     */
    public function testAppointment_TakeInWork(
        AcceptanceTester $I,
        AppointmentsRegistry $registry,
        NewAppointmentsPage $newAppointments
    ) {
        $newIncident = $this->incident;
        // $newIncident = new StdClass();
        // $newIncident->{"id"} = "i-20180726-095451";

        $registry->navigate();

        $id = $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $driver) use ($I, $newIncident, $registry) {
            $id = null;
            $driver->wait(15, 5000)->until(
                function () use ($driver, $I, $newIncident, $registry, &$id) {
                    $registry->navigate();
                    return $id = $I->grabTextFrom(
                        "//tr[contains(@class, 'grid-row') and .//div[contains(@class, 'grid-cell-content') and contains(., '$newIncident->id')]][position()=last()]//td[position()=1]//div[contains(@class, 'grid-cell-content')]"
                    );
                }
            );
            return $id;
        });
        $this->appointment->id = $id;
        codecept_debug($id);

        $newAppointments->navigate();
        $newAppointments->selectRow($id);
        $I->waitForElementVisible($newAppointments::$incidentsGrid."//*[contains(@class, 'grid-cell-content')]", 10);

        $I->see($this->incident->id,                $newAppointments::$incidentsGrid);
        $I->see($this->incident->address,           $newAppointments::$incidentsGrid);
        $I->see($this->incident->ksipType,          $newAppointments::$incidentsGrid);
        // $I->see($this->incident->ksipActualDate,    $newAppointments::$incidentsGrid);
        $I->see($this->incident->organization,      $newAppointments::$incidentsGrid);

        $form = $newAppointments->openTakeInWorkForm($id);
        $form->submit();
    }

    /**
     * @depends testAppointment_TakeInWork
     */
    public function testAppointment_DoneAndClose(
        AcceptanceTester $I,
        InWork $appointmentsInWorkPage,
        Finished $finishedAppointments
    ) {
        $appointmentsInWorkPage->navigate();
        $appointmentsInWorkPage->openAppointmentCard($this->appointment->id);
        $appointmentsInWorkPage->complete($this->appointment->id);

        sleep(5);
        $finishedAppointments->navigate();
        $finishedAppointments->close($this->appointment->id);
    }

    /**
     * @depends testAppointment_DoneAndClose
     */
    public function testIncident_DoneAndClose(
        AcceptanceTester $I,
        Process $processIncidentPage,
        FinishedIncidentsPage $finishedIncidentsPage
    ) {

        $processIncidentPage->navigate();
        $processIncidentPage->completeIncident($this->incident->id);

        $finishedIncidentsPage->navigate();
        $finishedIncidentsPage->closeIncident($this->incident->id);
    }
}
