<?php
namespace Component;

use \Codeception\Util\Locator;

class Select2
{
    /** @var \AcceptanceTester; */
    protected $tester;

    /** @var string[] */
    private $options;

    /**
     * @param string|array $locator Селектор элемента `div.select2-container`
     */
    public function __construct(\AcceptanceTester $I, $locator) {
        $this->tester = $I;
        $this->container = $locator;
    }

    public $container;
    public $results          = "#select2-drop ul.select2-results";
    public $resultsElement   = "#select2-drop ul.select2-results li .select2-result-label";

    /**
     * Раскрыть список
     */
    public function expand() {
        $I = $this->tester;
        $I->click($this->container);
        $I->waitForElementVisible($this->results, 5);
        $I->waitForElementVisible($this->resultsElement, 5);
        return $this;
    }

    /**
     * Скрыть список
     */
    public function hide() {
        $I = $this->tester;
        $I->executeJS(
            "document.querySelector('#select2-drop-mask').click()"
        );
        return $this;
    }

    /**
     * Читает текст опций
     * 
     * @param bool $cache (optional) Использовать кеш. По умолчанию `true`
     *                    Если передан `false`, то читает опции заново.
     */
    public function options($cache = true) {
        $I = $this->tester;
        if ($this->options == null || !$cache ) {
            $this->options = $I->grabMultiple($this->resultsElement);
        }
        return $this->options;
    }

    /**
     * Выбрать опцию по тексту
     * 
     * @param string $text Текст опции
     */
    public function select($text) {
        $I = $this->tester;
        $I->click($this->optionWithText($text));
        return $this;
    }

    /**
     * @param string $text Текст опции
     * 
     * @return string XPath опции, содержащей переданый текст.
     */
    private function optionWithText($text) {
        return Locator::contains($this->resultsElement, $text);
    }
}
