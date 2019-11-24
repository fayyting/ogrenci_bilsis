<?php

class PlacementReport extends DBObject{
    const TABLE = "placement_reports";
    public $ID, $property, $placement_date, $allocated, $keys_ready, $welcome_pack_ready, $client_contracted, $prior_notes,
    $arrive, $placement_notes, $welcome_pack_received, $pip_confirmation_signed, $guidelines_understood, $questionnaire_completed,
    $stopcock_info_understood, $all_keys_received, $property_accepted, $property_not_accepted_comment, $completed, $completed_in_time,
    $signed, $utilities_and_ctax_updated, $updated_voids, $updated_tenant_history, $updated_data_registry, $updated_property_details, $paperwork_filed_in_property;

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