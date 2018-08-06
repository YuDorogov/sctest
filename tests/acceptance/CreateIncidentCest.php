<?php

use \Page\Vis\ECOR_MO\Map as MapPage;

class CreateIncidentCest
{
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

    public function test(AcceptanceTester $I, MapPage $map)
    {
        $faker = $faker = Faker\Factory::create();

        $map->navigate();
        $form = $map->rightPanel()->openCreateIncidentForm();
        $form->fillAddressField("Республика Бурятия, г. Улан-Удэ, ул. Ленина, д. 2");
        $form->selectKsipType();

        $date1 = $I->grabValueFrom($form::$ksipActualDateField);
        $form->incident->ksipActualDate = $date1;

        $descr = $faker->sentence();
        $I->fillField($form::$ksipDescriptionField, $descr);
        $form->incident->ksipDescription = $descr;

        // $form->incident->ksipInitDate = $I->grabValueFrom($form::$ksipInitDateField);
        
        $comment = $faker->sentence();
        $I->fillField($form::$commentField, $comment);
        $form->incident->comment = $comment;

        $newIncident = $form->submit();

        $panel = $map->rightPanel();
        $I->waitForElementVisible($panel::$incidentElement, 15);

        $cardView = $panel->openIncidentCardView();

        $I->assertEquals($I->grabValueFrom($cardView::$incidentType),     $newIncident->ksipType);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentAddress),  $newIncident->address);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentState),    $newIncident->ksipActualDate.":00");
        $I->assertEquals($I->grabValueFrom($cardView::$incidentComment),  $newIncident->comment);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentDescriprion),  $newIncident->ksipDescription);
        $I->assertEquals($I->grabValueFrom($cardView::$incidentOrganisation), $newIncident->organization);
    }
}
