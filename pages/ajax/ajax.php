<?php

class AjaxController extends ServicePage{
    
    public function callService(string $service_name) {
        $this->$service_name();
    }
    
    public function check_access(): bool
    {
        $user = get_current_core_user();
        if(!$user->isRoot() && !$user->isUserInRole("ADMIN") && !$user->isUserInRole("MANAGER")){
            return FALSE;
        }
        return TRUE;
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
            ->condition(" $column LIKE :data AND $column != '' AND $column IS NOT NULL", [
                ":data" => $data
            ])->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT);
            if(isset($_POST["filter-column"]) && isset($_POST["filter-value"]) ){
                $filter_column = preg_replace('/[^a-zA-Z1-9_]*/', '', $_POST["filter-column"]);
                $query->condition( "$filter_column = :value AND $filter_column != '' AND $filter_column IS NOT NULL", 
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

    private function areaSelection(){
        $this->import_view("table_view");
        $table_data = [];
        $available_variables = array_chunk(PropertyArea::getAvailableOptionsForAreaType(), 4, true);
        array_walk($available_variables[0], function(&$el){ $el = "<b>$el</b>"; } );
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
        echo_table([],$table_data);
    }

    private function getNewAreaRow(){
        $this->import_view("file_input");
        $index = intval($_POST["index"]);
        $order = intval($_POST["order"]);
        $area = new PropertyArea();
        $area->area_type = htmlspecialchars($_POST["type"]);
        $area->measurement_type = "m2";
        echo "<tr><td>".implode("</td><td>",$area->getAreaTableRowData($index, TRUE, $order))."</td></tr>";
    }

    private function fireSafetyItemSelection(){
        $this->import_view("table_view");
        $fire_safety_items = FireSafetyItem::getAll([]);
        $safety_items = json_decode($_POST["fire_safety_items"] ? : "[]");
        $table_data = [];
        foreach($fire_safety_items as $index => $item){
            $item_info = !empty($safety_items) ? array_pop(array_filter($safety_items, function($el) use ($item){
                return $el->ID == $item->ID;
            })) : new stdClass();
            $table_data[$index/3][] = 
            "<div class='fire_safety_item_selection text-center'><br>
                <img src='".$item->getImageUrl()."' />
                <span class='glyphicon glyphicon-remove core-control fsi_count'>".intval($item_info->count)."</span>
                <input type='number' class='form-control hidden' value='".intval($item_info->count)."' data-item='$item->ID' ".($item_info->ID ? "checked": "")."/>
            </div>";
        }
        echo_table([], $table_data);
    }

    private function getUserInfoForLandlordSelection(){
        $user = get_current_core_user();
        $user_id = intval($_POST["user_id"]);
        $user_data = User::getUserById($user_id);
        echo json_encode([
            "NAME" => $user_data->NAME,
            "SURNAME" => $user_data->SURNAME,
            "PHONE" => $user_data->PHONE,
            "EMAIL" => $user_data->EMAIL,
            "address" => $user_data->address,
            "postcode" => $user_data->postcode
        ]);
    }

    private function getServiceProviderInfo(){
        $user = get_current_core_user();
        if(!$user->isRoot() && !$user->isUserInRole("ADMIN") && !$user->isUserInRole("MANAGER")){
            http_response_code(403);
            throw_exception_as_json(_t(114));
        }
        $provider_id = intval($_POST["service_provider_id"]);
        $provider = ServiceProvider::get(["ID" => $provider_id]);
        echo json_encode([
            "phone" => $provider->phone
        ]);
    }
    private function getDocumentComment(){
        $user = get_current_core_user();
        if(!$user->isRoot() && !$user->isUserInRole("ADMIN") && !$user->isUserInRole("MANAGER")){
            http_response_code(403);
            throw_exception_as_json(_t(114));
        }
        $document_id = intval($_POST["document_id"]);
        $document = PropertyDocument::get(["ID" => $document_id]);
        if(!$document){
            http_response_code(400);
            throw_exception_as_json(_t(67));
        }
        if(isset($_POST["document_comment"])){
            $document->document_comment = htmlspecialchars($_POST["document_comment"]);
            $document->update();
        }
        echo json_encode([
            "document_comment" => strval($document->document_comment)
        ]);
    }

    private function editFacilities(){
        $facilities = !empty($_POST["facilities"]) ? explode(";",$_POST["facilities"]) : [];
        $output = "";
        if(empty($facilities)){
            $this->printMessage("alert-info", _t(253));
        }else{
            $options = PropertyAreaFacility::getFacilityOptions();
            foreach($facilities as $facility){
                $output .= "<div class='col-sm-4'>".prepare_select_box($options,[
                    "default_value" => $facility,
                    "attributes" => [
                        "data-reference-table" => "area_facilities", 
                        "data-reference-column" => "facility_name",
                    ],
                    "classes" => ["autocomplete", "create_if_not_exist"]
                ]). "<a href='' class='glyphicon glyphicon-remove remove_facility'></a></div>";
            }
        }

        $output.= "<div class='col-sm-12'><input type='button' value='"._t(135)."' class='btn btn-info add_new_area_facility' /> </div>";

        echo $output;
    }

    private function getNewFacilityInput(){
        echo "<div class='col-sm-4'>".prepare_select_box(PropertyAreaFacility::getFacilityOptions(),[
            "attributes" => [
                "data-reference-table" => "area_facilities", 
                "data-reference-column" => "facility_name",
            ],
            "classes" => ["autocomplete", "create_if_not_exist"]
        ])."<a href='' class='glyphicon glyphicon-remove remove_facility'></a></div>";
    }

    private function getPropertyFinder(){
        $this->import_view("property_finder");
    }

    private function findProperty(){
        $filters = array_filter($_POST["property"], function($el){
            return $el && $el !== "NULL";
        });
        $properties_query = db_select(Property::TABLE, "p")
        ->leftjoin("property_statuses","ps", "p.status = ps.ID")
        ->leftjoin("property_type_a", "pta", "p.type = pta.ID")
        ->leftjoin("property_scheme_a","psa","p.scheme_a = psa.ID")
        ->leftjoin("property_scheme_b","psb","p.scheme_b = psb.ID")
        ->select("p", ["ID AS select_link","category", "adress", "postcode"])
        ->select("pta", ["explain AS pta_explain"])
        ->select("ps", ["explain AS ps_explain"])
        ->select("psa", ["explain AS psa_explain"])
        ->select("psb", ["explain AS psb_explain"])
        ->limit(PAGE_SIZE_LIMIT)
        ->orderBy("p.ID");
        foreach($filters as $column => $filter){
            $column =  preg_replace("/[^a-z_]+/", "", $column);
            if($column == "adress"){
                $properties_query->condition("$column LIKE :$column",[":$column" => "%$filter%"]);
            }else{
                $properties_query->condition("$column = :$column AND $column IS NOT NULL AND $column != ''",[":$column" => $filter]);
            }
        }
        $headers = [
            "#",
            _t(227),
            _t(119),
            _t(133),
            _t(116),
            _t(117),
            _t(122)." A",
            _t(122)." B"
        ];
        $results = $properties_query->execute()->fetchAll(PDO::FETCH_OBJ);
        $property_category_map = Property::getAvailableCategoryOptions();
        foreach($results as &$row){
            $row->select_link = "<input type='button' class='btn btn-info property_finder_select' data-select-id='$row->select_link' value='"._t(347)."' />";
            $row->category = $property_category_map[$row->category];
        }

        $this->import_view("table_view");
        echo_table($headers, $results);
    }

    private function getPropertyInfoForPropertySection(){
        $property = Property::get(["ID" => $_POST["property"]]);
        if(!$property->ID){
            throw_exception_as_json(_t(349));
        }
        $property_scheme_a = $property->scheme_a ? DBObject::get(["ID" => $property->scheme_a], "property_scheme_a") : NULL;
        $property_scheme_b = DBObject::get(["ID" => $property->scheme_b], "property_scheme_b");

        $property_type = DBObject::get(["ID" => $property->type], "property_type_a");
        $result_data = [
            "pf_id" => $property->ID,
            "pf_council" => $property_scheme_a->explain,
            "pf_adress" => $property->adress,
            "pf_postcode" => $property->postcode,
            "pf_scheme_a" => $property_scheme_a->shortcode,
            "pf_scheme_b" => $property_scheme_b->shortcode,
            "pf_type" => $property_type->shortcode,
            "floor" => $property->floor
        ];
        echo json_encode($result_data);
    }
}