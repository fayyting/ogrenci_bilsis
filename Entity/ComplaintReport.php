<?php

class ComplaintReport extends DBObject{
    const TABLE = "complaints";
    public $ID, $date_received, $reported_via, $description, $immadiate_or_foreseable_risks, $risks_of_breakdown_in_meeting_expectations, $risks_to_any_persons_wellbeing,
    $risk_of_legislative_non_compliance, $risk_to_any_property, $pass_info_third_party, $risk_of_enviromental_impact, $intervention_of_thirty_party_required, 
    $factors_to_complaint, $immadiate_action_has_been_taken, $complaint_been_resolved;

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