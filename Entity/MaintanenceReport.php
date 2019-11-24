<?php

class MaintanenceReport extends DBObject{
    const TABLE = "maintenance_reports";
    public $ID, $property, $reported, $issue_notes, $appointment, $arrive, $notes, $expenses, $completed, $signed, $completed_in_time, 
    $lonlord_expense, $tenant_expense, $third_party_expense, $expenses_added, $care, $compliance, $health_safety, $enviromental, $sorting, $status;

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

    public static function getAvailableVariablesForStatus(){
        return [
                "open" => _t(340),
                "closed" => _t(341)
        ];
    }
}