<?php

class PropertyDocument extends DBObject{
    const TABLE = "property_documents";
    public $ID, $property, $document_type, $required, $received, $document_comment, $checked;

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

    public function updateDocumentFiles($document_file_tmp_names, $document_file_names, $document_index){
        $files_to_remove = $_POST["document_files"][$document_index]["files"] ? array_keys($_POST["document_files"][$document_index]["files"]) : [];
        if(count($files_to_remove)){
            $current_files = $this->getFiles();
            foreach($files_to_remove as $file_to_remove){
                $current_files[$file_to_remove]->delete();
            }
        }
        foreach($document_file_tmp_names as $index => $document_file_tmp_name){
            if(!$document_file_tmp_name) return;
            $document = new PropertyDocumentFile();
            $document->document = $this->ID;
            if($document->importFile($document_file_tmp_name, $document_file_names[$index])){
                $document->insert();
            }
        }
    }

    public function getFiles(){
        return PropertyDocumentFile::getAll(["document" => $this->ID]) ? : [];
    }

    public function getFilesRendered($document_index){
        $document_files = $this->getFiles();
        $render = "";
        foreach($document_files as $index => $file){
            $render .= "<div class='dbl_click_file'><a href='".$file->getFileUrl()."' download='$file->file_name'>".$file->getFileIcon()."$file->file_name</a>
                            <a href='' class='remove_document' data-connected-name='document_files[$document_index][files][$index]'><span class='glyphicon glyphicon-remove core-control'></span></a>
                        </div>";
        }
        $render .= self::getDocumentFileInput($document_index);
        return $render;
    }

    public static function getDocumentFileInput($document_index){
        return get_file_input("documents[$document_index][files][0]", "", 
                            [
                                "label" => "<span class='glyphicon glyphicon glyphicon-paperclip'></span>",
                                "classes" => ["document-file-input"],
                                "attributes" => ["data-document-index" => $document_index, "data-file-index" => "0"]
                            ]);
    }
}