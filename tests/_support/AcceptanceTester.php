<?php

use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy as By;
use \Symfony\Component\CssSelector\CssSelectorConverter;
use \Codeception\Util\Locator;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    public function countVisible($selector) {
        $I = $this;

        $xpath = $this->toXPath($selector);

        return $I->executeInSelenium(function(RemoteWebDriver $wd) use ($xpath) {
            $els = $wd->findElements(By::xpath($xpath));

            $visibleEls =  array_filter($els, function($el) {
                return $el->isDisplayed();
            });

            return count($visibleEls);
        });
    }

    // TODO: mv to Utils
    public function toXPath($selector) {
        try {
            $xpath = (new CssSelectorConverter())->toXPath($selector);
            return $xpath;
        } catch (ParseException $e) {
            if (Locator::isXPath($selector)) {
                return $selector;
            }
        }
        return null;
    }
}
