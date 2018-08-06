<?php

namespace Fragment\Vis\ECOR_MO\Map;

class IncidentCardView {
    /**
     * @var \AcceptanceTester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    public static $incidentType     = "#kitform-Marker-popup input[name='incidentType.name']";    // Тип КСиП
    public static $incidentAddress  = "#kitform-Marker-popup input[name='address']";              // Местоположение КСиП
    public static $incidentState    = "#kitform-Marker-popup input[name='state']";                // Дата и время возникновения КСиП
    public static $incidentCreation = "#kitform-Marker-popup input[name='timeCreate']";           // Дата и время создания инцидента
    
    public static $incidentComment      = "#kitform-Marker-popup textarea[name='parentEvent.comment']";     // Комментарий
    public static $incidentDescriprion  = "#kitform-Marker-popup textarea[name='comment']";                 // Описание КСиП
    public static $incidentOrganisation = "#kitform-Marker-popup input[name='parentEvent.status.name']";    // Организация
}
