<?php

class ServiceProvider extends DBObject{
    const TABLE = "service_providers";
    public $ID, $provider_name, $provider_type, $phone;

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