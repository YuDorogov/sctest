<?php

namespace Fragment;

use \Codeception\Util\Locator;
use \Model\Incident;
use \Component\Select2;

class CreateIncidentForm {
    /** @var \AcceptanceTester */
    protected $tester;

    /** @var \Model\Incident */
    public $incident;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
        $this->incident = new incident();
    }

    public static $card = "#ecov form.formView.form-horizontal";

    public static $addressField                 = ".form-element[data-bind-property-name='address'] .form-group.input input";
    public static $addressAutocompleteList      = ".form-element[data-bind-property-name='address'] .address-complete ul";
    public static $addressAutocompleteElement   = ".form-element[data-bind-property-name='address'] .address-complete li";

    public static $ksipActualDateField  = "input[name='infoCard.timeStart']";
    public static $ksipDescriptionField = "textarea[name='infoCard.comment']";

    public static $ksipInitDateField    = "input[name='callInfo.timeStart']";
    public static $commentField         = "textarea[name='comment']";

    public static $submitButton = "button[action='CreateByECOR']";

    public static $ksipType_select2 = "#s2id_ksipType";

    public function fillAddressField($text) {
        $I = $this->tester;

        $I->fillField(static::$addressField, $text);
        $I->waitForElementVisible(static::$addressAutocompleteList, 15);
        sleep(2);

        $c = $I->countVisible(static::$addressAutocompleteElement);
        $index = mt_rand(1, $c);

        $addressSelector = Locator::elementAt(static::$addressAutocompleteElement, $index);
        $selectesAddress = $I->grabTextFrom($addressSelector);
        codecept_debug("\tDEBUG | selected address: ".$selectesAddress);
        $I->click($addressSelector);

        $this->incident->address = $selectesAddress;
    }

    public function fillDescriptionField($text) {
        $I = $this->tester;
        $I->fillField(static::$ksipDescriptionField, $text);
        $this->incident->ksipDescription = $text;
    }

    public function fillCommentField($text) {
        $I = $this->tester;
        $I->fillField(static::$commentField, $text);
        $this->incident->comment = $text;
    }

    public function selectKsipType($typeText = "") {
        $I = $this->tester;
        $select2 = new Select2($I, static::$ksipType_select2);
        $select2->expand();
        $select2->options();
        $select2->hide();

        foreach($select2->options() as $option) {
            codecept_debug($option);
            $select2->expand();
            $select2->select($option);
            sleep(2);
            try {
                $I->waitForElementVisible(".table-incidents tr.grid-row.data", 5);
                continue;
            } catch(\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                $this->incident->ksipType = $option;
                codecept_debug($option);
                codecept_debug("--------------------------------------------");
                return;
            }
        }
        throw new Exception("Созданы инциденты для каждого типа КСиП");
    }

    public function submit() {
        $I = $this->tester;
        $I->click(static::$submitButton);
        $I->waitForElementVisible("#kitform-selectOrganization", 10);

        $org = $I->grabTextFrom("#kitform-selectOrganization .select2-choice");

        $this->incident->organization = $org;
        $I->click("#kitform-selectOrganization button#ok");
        return $this->incident;
    }
}
