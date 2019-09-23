<?php

class AjaxController extends ServicePage{
    
    public function callService(string $service_name) {
        $this->$service_name();
    }
    
    
    /**
     * Select from table
     */
    private function select(){
        if(in_array( $_POST["table"], get_information_scheme()) ){
            $columns = db_select($_POST["table"])->orderBy("ID")->execute()->fetchAll(PDO::FETCH_NUM);
            $result = ["values" => $columns];
            $result["skeleton"] = get_table_description($_POST["table"]);
            echo json_encode($result);
        }
    }

     /**
     * Delete record
     */
    private function delete(){
        if(in_array( $_POST["table"], get_information_scheme()) ){
             $table = $_POST["table"];
             unset($_POST["table"]);
             $values = $_POST;
             $object = new DBObject($table);
             object_map($object, $values);
             $object->delete();
         }
    }
    
    /**
     * Returns table list
     */
    private function get_table_list(){
        echo json_encode(get_information_scheme());
    }
    
    /**
     * Returns table description
     */
    private function get_description(){
        echo json_encode(get_table_description($_POST["table"]));
    }
    
    /**
     * returns foreign key description
     */
    private function get_fk_description(){
        $table = $_POST["table"];
        if(in_array($table, get_information_scheme())) {
            $description = get_foreign_key_description($table, $_POST["key"])->fetch(PDO::FETCH_NUM);
            $keys = db_select($description[0])->select("", [$description[1]])->orderBy("ID")->execute()->fetchAll(PDO::FETCH_NUM);
            $entry = db_select($description[0])->orderBy("ID")->execute()->fetchAll(PDO::FETCH_NUM);
            echo json_encode(["status" => "true", "keys" => $keys, "entry" => $entry]);
        }
    }
    
    /**
     * Returns foreign key entry
     */
    private function get_fk_entry() {
        $description = get_foreign_key_description($_POST["table"], $_POST["column"])->fetch(PDO::FETCH_NUM);
        $object = new DBObject($description[0]);
        $object->getById(intval($_POST["fk"]));
        $return_string = "";
        foreach ( convert_object_to_array($object) as $key => $field){
            $return_string.= "$key = $field ";
        }
        echo $return_string;
    }
    
    /**
     * Removes a manual document
     */
    function remove_document(){
        if( isset($_POST["id"]) ){
            $document = new DBObject(DOCUMENTS);
            $document->ID = intval($_POST["id"]);
            $document->delete();
            send_result(_t(65));
        }
    }
    
    /**
     * Returns field definition for new table definition
     */
    private function get_input_field(){
        require "views/field_definition.php";
        echo_field_definition();
    }
    
    /**
     * Makes new table definition
     */
    private function new_table_definition(){
        $table_name =  preg_replace("/[^a-z1-9_]+/", "", $_POST["table_name"]);
        $fields = $_POST["fields"];
        if(in_array($table_name, get_information_scheme())){
            throw_exception_as_json(_t(66));
        }
        $constants = [];
        $references = [];
        $query = "CREATE TABLE `{$table_name}` ( ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
        foreach ($fields as $field){
            $field["field_name"] = preg_replace("/[^a-z1-9_]+/", "",$field["field_name"]);
            $query .= ", `".$field["field_name"]."` ";
            if($field["field_type"] === "VARCHAR"){
                $query.= "VARCHAR(".intval($field["field_length"]).")";
            }else if(in_array($field["field_type"], ["INT", "DOUBLE", "TEXT", "DATE", "DATETIME", "TIME", "TINYTEXT", "LONGTEXT"])){
                $query.= $field["field_type"];
            }else if($field["field_type"] == "MUL" && in_array($field["mul_table"], get_information_scheme())){
                $query.= "INT";
                array_push($references, [$field["field_name"], $field["mul_table"]]);
            }else {
                throw_exception_as_json(_t(67));
            }
            
            if(isset($field["is_unique"]) && $field["is_unique"] === "on"){
                array_push($constants, $field["field_name"]);
            }
        }
        foreach ($references as $reference){
            $query.= ", FOREIGN KEY (`$reference[0]`) REFERENCES `$reference[1]`(ID) ";
        }
        foreach ($constants as $constant){
            $query.= ", UNIQUE (`$constant`) ";
        }
        $query.= ") ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";
        CoreDB::getInstance()->query($query);
        
        send_result(_t(68), "message");
    }
    
    /**
     * Drops table
     */
    private function drop(){
        $tablename = $_POST["tablename"];
        if(in_array($tablename, get_information_scheme())){
            CoreDB::getInstance()->query("DROP TABLE `$tablename`");
            echo json_encode(["status" => "true", "message" => _t(69, [$tablename])]);
        }
        CoreDB::getInstance()->commit();
    }
    
    /**
     * Truncates table
     */
    private function truncate(){
        $tablename = $_POST["tablename"];
        if(in_array($tablename, get_information_scheme())){
            db_truncate($tablename);
            echo json_encode(["status" => "true", "message" => _t(110, [$tablename])]);
        }
        CoreDB::getInstance()->commit();
    }
    
    /**
     * Adds new column to table
     */
    private function alter_table(){
        $tablename = $_POST["tablename"];
        $fields = $_POST["fields"];
        $csrf_table = get_csrf($_POST["form_build_id"], "new_table");
        if(in_array($tablename, get_information_scheme()) && $tablename === $csrf_table ){
            $queries = [];
            foreach ($fields as $field){
                $query = "ALTER TABLE `$tablename` ADD `".$field['field_name']."` ";
                if($field["field_type"] === "VARCHAR"){
                    $query.= "VARCHAR(".intval($field["field_length"]).") CHARACTER SET utf8 COLLATE utf8_general_ci;";
                }else if(in_array($field["field_type"], ["INT", "DOUBLE", "TEXT", "DATE", "DATETIME", "TIME", "TINYTEXT", "LONGTEXT"])){
                    $query.= $field["field_type"].";";
                }else if($field["field_type"] == "MUL" && in_array($field["mul_table"], get_information_scheme())){
                    $query .= "INT; ";
                    array_push($queries, $query);
                    $query = "ALTER TABLE $tablename ADD FOREIGN KEY (`".$field["field_name"]."`) REFERENCES ".$field["mul_table"]."(ID)";
                }else {
                    throw_exception_as_json(_t(67));
                }
                array_push($queries, $query);
                if(isset($field["is_unique"]) && $field["is_unique"] === "on"){
                    $query = "ALTER TABLE $tablename ADD UNIQUE(`".$field['field_name']."`)";
                    array_push($queries,$query);
                }
            }
            $db = CoreDB::getInstance();
            try{
                $db->beginTransaction();
                foreach ($queries as $query){
                    $db->query($query);
                }
                $db->commit();
            } catch (Exception $ex){
                throw_exception_as_json($query);
            }
            send_result(_t(32), "message");
            
        }else {
            throw_exception_as_json(_t(67));
        }
    }
    /**
     * Removes user
     */
    private function delete_user(){        
        $username = $_POST["USERNAME"];
        if($user_to_delete = User::getUserByUsername($username)){
            CoreDB::getInstance()->beginTransaction();
            $user_to_delete->delete();
            CoreDB::getInstance()->commit();
            send_result(_t(70, [$user_to_delete->USERNAME]));
        }
    }
    
    /**
     * Removes role
     */
    private function remove_role(){
        $role = new DBObject(ROLES);
        $role->getById( User::getIdOfRole($_POST["ROLE"]));
        $role->ROLE != $_POST["ROLE"] ? throw_exception_as_json(_t(67)) : NOEXPR;
        $user = db_select(USERS_ROLES)->condition("ROLE_ID = :role_id", ["role_id" => $role->ID])->limit(1)->execute()->fetchAll(PDO::FETCH_NUM);
        if(count($user) > 0){
            throw_exception_as_json(_t(71));
        }
        $role->delete();
        
        send_result(_t(72));
    }
    
    private function langimp() {
        try{
            $translations = json_decode(file_get_contents(Translator::BACKUP_PATH));
            CoreDB::getInstance()->beginTransaction();
            db_truncate(TRANSLATIONS);
            foreach ($translations as $translation){
                    db_insert(TRANSLATIONS, (array) $translation)->execute();
            }
            send_result(_t(107));
        } catch (Exception $ex) {
            throw_exception_as_json($ex->getMessage());
        }
    }
    
    private function langexp() {
        try{
            $translations = db_select(TRANSLATIONS)->execute()->fetchAll(PDO::FETCH_ASSOC);
            if(file_exists(Translator::BACKUP_PATH)){
                unlink(Translator::BACKUP_PATH);
            }
            file_put_contents(Translator::BACKUP_PATH, json_encode($translations, JSON_PRETTY_PRINT));
            send_result(_t(106));
        } catch (Exception $ex) {
            throw_exception_as_json($ex->getMessage());
        }
    }

    private function AutoCompleteSelectBoxFilter(){
        $table = $_POST["table"];
        
        if(in_array($table, get_information_scheme()) ){
            $column = preg_replace('/[^a-zA-Z1-9_]*/', '', $_POST["column"]); ;
            $data = "%".$_POST["data"]."%";
            $query = db_select($table)
            ->select($table, ["ID", $column])
            ->condition(" $column LIKE :data", [
                ":data" => $data
            ])->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT);
            if(isset($_POST["filter-column"]) && isset($_POST["filter-value"]) ){
                $query->condition(escapeshellarg($_POST["filter-column"])." = :value", 
                [":value" => $_POST["filter-value"]]);
            }
            $filtered_result = $query->execute()->fetchAll(PDO::FETCH_NUM);
            echo json_encode($filtered_result);
        }
    }

    /**
     * Delete record
     */
    private function deleteProperty(){
        $values = $_POST;
        $property = new Property();
        object_map($property, $values);
        $property->delete();
    }

    private function areaSelectionByType(){
        $type = $_GET["type"];
        if(!in_array($type, ["general_living_area", "bathroom", "parking", "bedroom", "gardens"])){
            http_response_code(400);
            return ;
        }
        $this->import_view("table_view");
        $table_data = [];
        switch($type){
            case "general_living_area":
                $available_variables = array_chunk(PropertyArea::getAvailableVariablesForLivingArea(), 3, true);
                foreach($available_variables as $row_num => $row){
                    $table_data[$row_num] = [];
                    foreach($row as $key=> $data){
                        $table_data[$row_num][] = '<button class="area_selection_button" data-type="'.$key.'">'.$data.'</button>';
                    }
                }
                $table_data[$row_num][] = '<label for="living_area_other">'._t(184).' :</label>
                                            <input type="text" id="living_area_other" class="form-control"/>
                                            <button class="area_selection_button hidden" data-type=""></button>
                                            <button class="area_other_selection_button" data-type="">'._t(77).'</button>';
                break;
            case "bathroom":
                $table_data[] = [
                    _t(221).":",
                    '<label class="bathroom_selection">
                        <input type="radio" name="bath" value="none" checked/>
                        '._t(137).'
                    </label>',
                    '<label class="bathroom_selection">
                        <input type="radio" name="bath" value="with_shower_bath" checked/>
                        <img src="'.BASE_URL.'/assets/bathroom.png">
                    </label>',
                    '<label class="bathroom_selection">
                        <input type="radio" name="bath" value="without_shower_bath"/>
                        <img src="'.BASE_URL.'/assets/bathroom2.png">
                    </label>'
                ];
                $table_data[] = [
                    _t(222).":",
                    '<label class="bathroom_selection">
                        <input type="radio" name="shower" value="none" checked/>
                        '._t(137).'
                    </label>',
                    '<label class="bathroom_selection">
                        <input type="radio" name="shower" value="with_shower"/>
                        <img src="'.BASE_URL.'/assets/shower.png">
                    </label>'
                ];
                $table_data[] = [
                    _t(223).":",
                    '<label class="bathroom_selection">
                        <input type="radio" name="toilet" value="none" checked/>
                        '._t(137).'
                    </label>',
                    '<label class="bathroom_selection">
                        <input type="radio" name="toilet" value="with_toilet"/>
                        <img src="'.BASE_URL.'/assets/toilet.png">
                    </label>
                    <button class="area_selection_button hidden"></button>'
                ];
                break;
            case "parking":
                $available_variables = PropertyArea::getAvailableVariablesForParking();
                $table_data[0] = [];
                foreach($available_variables as $key => $value){
                    $table_data[0][] = '<button class="area_selection_button" data-type="'.$key.'"><img src="'.BASE_URL.$value.'"></button>';
                }
                break;
            case "bedroom":
                $table_data[0] = [
                    '<button class="area_selection_button" data-type="1">1</button>',
                    '<button class="area_selection_button" data-type="2">2</button>',
                    '<button class="area_selection_button" data-type="3">3</button>'
                ];
                break;
            case "gardens":
                $available_variables = PropertyArea::getAvailableVariablesForGardens();
                $table_data[0] = [];
                foreach($available_variables as $key => $value){
                    $table_data[0][] = '<button class="area_selection_button" data-type="'.$key.'"><img src="'.BASE_URL.$value.'"></button>';
                }
                break;
        }
        echo_table([],$table_data);
    }

    private function fireSafetyItemSelection(){
        $this->import_view("table_view");
        $fire_safety_items = FireSafetyItem::getAll([]);
        $table_data = [];
        foreach($fire_safety_items as $index => $item){
            $table_data[$index/3][] = 
            "<label class='fire_safety_item_selection text-center'><br>
                <img src='".$item->getImageUrl()."' />
                <input type='checkbox' value='{$item->ID}'/>
            </label>";
        }
        echo_table([], $table_data);
    }
}