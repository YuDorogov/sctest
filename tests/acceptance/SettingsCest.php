<?php

use \Page\Settings;

class SettingsCest
{
    public function _before(AcceptanceTester $I)
    {
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

    /**
     * @group exclude
     */
    public function set(AcceptanceTester $I, \Page\Settings $setting)
    {
        $setting->navigate();
        $visFormPanel = $setting->openEditFormFor("GAsterUrl");
        $visFormPanel->fillValue("tt.smartcitycloud.ru/gaster");
        $visFormPanel->submit();
        $setting->dontSeeEditForm();

        $visFormPanel = $setting->openEditFormFor("SmartBusHost");
        $visFormPanel->fillValue("cb.tt.smartcitycloud.ru:8000");
        $visFormPanel->submit();
        $setting->dontSeeEditForm();
    }
}
