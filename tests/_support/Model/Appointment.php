<?php

namespace Model;

class Appointment {
    public $id;         // cs-12345678-123456
    public $stepNum;    // Шаг
    public $stepDescr;  // Описание шага
    public $stepTime;   // Регламентное время выполнения шага (мин)
    public $stepType;   // Тип шага
}