<?php

class InspectionReport extends DBObject{
    const TABLE = "inspection_reports";
    public $ID, $property,$appointment, $allocated, $arrived, $tenant_present, $damages_or_defects, $adequate_proof_of_occupation,
    $lifestyle_damp, $clean_and_tidy, $outstanding_maintanence_issues, $fire_alarm_tested_and_operating, $safeguarding_issues,
    $satisfied_property, $tenant_has_concerns_or_complaints, $satisfied_service_delivery, $satisfied_tenant;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @Override
     */
    public static function get(array $filter, string $table = self::TABLE){
        return parent::get($filter, self::TABLE);
    }

    public static function getAll(array $filter, string $table = self::TABLE) : array
    {
        return parent::getAll($filter, $table);
    }
}