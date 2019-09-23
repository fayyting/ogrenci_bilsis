<?php

class PropertyDocument extends DBObject{
    const TABLE = "property_documents";
    public $ID, $property, $document_type, $required, $received, $document, $document_name;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    
    public static function get(array $filter, string $table = self::TABLE){
        return parent::get($filter, self::TABLE);
    }

    public static function getAll(array $filter, string $table = self::TABLE) : array
    {
        return parent::getAll($filter, $table);
    }

    public function getDocumentType(){
        return PropertyDocumentType::getById($this->document_type);
    }

    public function getDocumentUrl(){
        return $this->get_file_url_for_field("document");
    }

    public function updateDocument(string $tmp_name, string $name)
    {
        if($this->document){
            remove_uploaded_file($this->table, "document", $this->document);
        }
        $file_url = "files/uploaded/$this->table/document/";
        is_dir($file_url) ?  : mkdir($file_url, 0777, true);
        $file_name = md5($tmp_name);
        move_uploaded_file($tmp_name, $file_url.$file_name);
        $this->document = $file_name;
        $this->document_name = $name;
        return true;
    }
}