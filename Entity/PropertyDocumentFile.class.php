<?php

class PropertyDocumentFile extends DBObject{
    const TABLE = "property_document_files";
    public $ID, $document, $file, $file_name;

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
    
    public function getFileUrl(){
        return $this->get_file_url_for_field("file");
    }

    public function importFile(string $tmp_name, string $name){
        if($this->file){
            remove_uploaded_file($this->table, "file", $this->file);
        }
        $file_url = "files/uploaded/$this->table/file/";
        is_dir($file_url) ?  : mkdir($file_url, 0777, true);
        $file_name = md5($tmp_name);
        move_uploaded_file($tmp_name, $file_url.$file_name);
        $this->file = $file_name;
        $this->file_name = $name;
        return true;
    }
    public function getFileIcon(){
        $fsize = 25; //icon px width in output
        switch (pathinfo($this->file_name)['extension']) {
            case 'pdf':
                $img = 'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/pdf.png';
                break;
            case 'doc':
            case 'docx':
                $img = 'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Word.png';
                break;
            case 'txt':
                $img = 'http://cdn1.iconfinder.com/data/icons/CrystalClear/128x128/mimetypes/txt2.png';
                break;
            case 'xls':
            case 'xlsx':
            case 'xlsm':
                $img = 'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20Excel.png';
                break;
            case 'ppt':
            case 'pptx':
                $img = 'http://cdn2.iconfinder.com/data/icons/sleekxp/Microsoft%20Office%202007%20PowerPoint.png';
                break;
            case 'mp3':
                $img = 'http://cdn2.iconfinder.com/data/icons/oxygen/128x128/mimetypes/audio-x-pn-realaudio-plugin.png';
                break;
            case 'wmv':
            case 'mp4':
            case 'mpeg':
                $img = 'http://cdn4.iconfinder.com/data/icons/Pretty_office_icon_part_2/128/video-file.png';
                break;
            case 'html':
                $img = 'http://cdn1.iconfinder.com/data/icons/nuove/128x128/mimetypes/html.png';
                break;
            case "jpg":
            case "jpeg":
            case "png":
                $img = "https://cdn2.iconfinder.com/data/icons/pittogrammi/142/32-256.png";
                break;
            default:
                $img = 'https://cdn0.iconfinder.com/data/icons/documents-50/32/undefined-document-256.png';
                break;
        }   
        
        return "<img src='$img' width='$fsize' />";
    }
}