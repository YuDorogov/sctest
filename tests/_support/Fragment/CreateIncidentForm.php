<?php

namespace Fragment;

use \Codeception\Util\Locator;
use \Model\Incident;

class CreateIncidentForm {
    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    /**
     * @var \Model\Incident
     */
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

    public function fillAddressField($text) {
        $I = $this->tester;

        $I->fillField(static::$addressField, $text);
        $I->waitForElementVisible(static::$addressAutocompleteList, 15);

        $c = $I->countVisible(static::$addressAutocompleteElement);
        $index = rand(1, $c);

        $addressSelector = Locator::elementAt(static::$addressAutocompleteElement, $index);
        $selectesAddress = $I->grabTextFrom($addressSelector);
        $I->click($addressSelector);

        $this->incident->address = $selectesAddress;
    }

    public function selectKsipType($typeText = "") {
        $I = $this->tester;

        $I->click("#s2id_ksipType");
        $select2ResultList = "#select2-drop ul.select2-results";
        $I->waitForElementVisible($select2ResultList, 15);

        $select2ResultElement = $select2ResultList." li .select2-result-label";
        $I->waitForElementVisible($select2ResultElement, 15);

        $optionsArr = $I->grabMultiple($select2ResultElement);
        codecept_debug($optionsArr);
        $I->click("#select2-drop-mask");

        foreach($optionsArr as $option) {
            $I->click("#s2id_ksipType");
            $I->waitForElementVisible($select2ResultList, 15);
            $I->waitForElementVisible($select2ResultElement, 15);

            $ksipType = Locator::contains($select2ResultElement, $option);
            $I->click($ksipType);
            sleep(2);
            try {
                $I->waitForElementVisible(".table-incidents tr.grid-row.data", 15);
                continue;
            } catch(\Facebook\WebDriver\Exception\NoSuchElementException $e) {
                $this->incident->ksipType = $option;
                return;
            }
        }
        throw new Exception("Созданы инциденты для каждого типа КСиП");

        // $c = $I->countVisible($select2ResultElement);
        // $index = rand(1, $c);

        // $ksipType = Locator::elementAt($select2ResultElement, $index);
        // $selectesType = $I->grabTextFrom($ksipType);

        // $I->click($ksipType);

        // $this->incident->ksipType = $selectesType;
    }

    public function submit() {
        $I = $this->tester;
        $I->click(static::$submitButton);
        $I->waitForElementVisible("#kitform-selectOrganization", 10);

        $I->click("#kitform-selectOrganization .select2-choice");
        $select2ResultList = "#select2-drop ul.select2-results";
        $I->waitForElementVisible($select2ResultList, 15);

        $select2ResultElement = $select2ResultList." li .select2-result-label";
        $I->waitForElementVisible($select2ResultElement, 15);
        $c = $I->countVisible($select2ResultElement);
        $index = rand(1, $c);

        $org = Locator::elementAt($select2ResultElement, $index);
        $selecteOrg = $I->grabTextFrom($org);

        $I->click($org);

        $this->incident->organization = $selecteOrg;

        $I->click("#kitform-selectOrganization button#ok");

        return $this->incident;
    }
}
