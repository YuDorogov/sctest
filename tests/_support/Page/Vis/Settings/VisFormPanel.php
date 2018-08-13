<?php
namespace Fragment\Settings;

use \Codeception\Util\Locator;

class VisFormPanel
{
    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $panel    = ".vis-form-panel";
    public static $form     = ".vis-form-panel form.formView.form-horizontal";

    public static $nameField        =  ".form-input[data-bind-property-name='name'] input";
    public static $valueField       =  ".form-input[data-bind-property-name='value'] input";
    public static $descriptionField =  ".form-input[data-bind-property-name='description'] textarea";

    public static $submitButton = "button[ng-click='submitForm()']";

    public function fillName($text) {
        $I = $this->tester;
        $I->fillField(static::$nameField, $text);
    }

    public function fillValue($text) {
        $I = $this->tester;
        $I->fillField(static::$valueField, $text);
    }

    public function fillDescription($text) {
        $I = $this->tester;
        $I->fillField(static::$descriptionField, $text);
    }

    public function submit() {
        $I = $this->tester;
        $I->click(static::$submitButton);
    }
}
