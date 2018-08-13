<?php

namespace Fragment;

use \Codeception\Util\Locator;
use \Model\Appointment;
use \Component\Select2;

class CreateAppointmentForm {
    /** @var \AcceptanceTester */
    protected $tester;

    /** @var \Model\Appointment */
    private $appointment;

    /** @var \Component\Select2 */
    private $stepType; // Тип шага

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
        $this->appointment = new \Model\Appointment;

        $this->stepType = new \Component\Select2(
            $I,
            "#kitform-scriptStepAdd .panel-group:not(.hide) .select2-container"
        );
    }

    public static $panel        = "#kitform-scriptStepAdd";
    public static $stepNum      = "#kitform-scriptStepAdd input[name='number']";            // Шаг
    public static $stepDescr    = "#kitform-scriptStepAdd textarea[name='description']";    // Описание шага
    public static $stepTime     = "#kitform-scriptStepAdd input[name='time']";              // Регламентное время выполнения шага (мин)
    public static $submitButton = "#kitform-scriptStepAdd button#save";
    
    public function fillStepNum($text) {
        $I = $this->tester;
        $I->fillField(static::$stepNum, $text);
        $this->appointment->stepNum = $text;
    }

    public function fillStepDescr($text) {
        $I = $this->tester;
        $I->fillField(static::$stepDescr, $text);
        $this->appointment->stepDescr = $text;
    }

    public function fillStepTime($text) {
        $I = $this->tester;
        $I->fillField(static::$stepTime, $text);
        $this->appointment->stepTime = $text;
    }

    public function selectStepType($option) {
        $this->stepType->expand()->select($option);
        $this->appointment->stepType = $option;
    }

    public function submit() {
        $I = $this->tester;
        $I->click(static::$submitButton);
        $I->waitForElementNotVisible(static::$panel, 10);
        $I->dontSeeElementInDOM(static::$panel);
        return $this->appointment;
    }
}
