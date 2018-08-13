<?php

namespace Page;

class BasePage {
    public function navigate() {
        $I = $this->tester;
        $I->amOnPage(static::$URL);
        $this->validate();
    }

    public function validate() {
        return;
    }
}