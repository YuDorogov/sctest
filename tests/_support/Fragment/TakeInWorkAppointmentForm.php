<?php

namespace Fragment;

use \Codeception\Util\Locator;

class TakeInWorkAppointmentForm {
    /** @var \AcceptanceTester */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $form = "#kitform-OrderTakeInWork form";
    public static $submitButton = "#kitform-OrderTakeInWork form button#takeInWork";

    public function submit() {
        $I = $this->tester;
        $I->click(static::$submitButton);
        $I->waitForElementNotVisible(static::$form, 10);
    }
}
