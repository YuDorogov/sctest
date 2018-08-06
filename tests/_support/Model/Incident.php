<?php

namespace Model;

class Incident {
    public $id;                 // ID Инцидента
    public $address;            // Местоположение КСиП 
    public $ksipType;           // Тип КСиП
    public $ksipActualDate;     // Дата и время возникновения КСиП
    public $ksipDescription;    // Описание КСиП
    public $ksipInitDate;       // Дата и время поступления информации о КСиП
    public $comment;            // Комментарий
    public $organization;       // Организация
}