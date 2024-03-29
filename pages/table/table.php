<?php
class TableController extends AdminPage{
    const TEXT_SIZE_LIMIT = 255;
    private $newController;
    protected function preprocessPage() {
        parent::preprocessPage();
        if(isset($this->arguments[0]) && $this->arguments[0] == "new"){
            require 'new/new.php';
            $this->newController = new NewTableController($this->arguments);
            return;
        }
        $table_exist = TRUE;
        if(isset($this->arguments[0])){
           $table_exist = in_array($this->arguments[0], get_information_scheme());
        }
        if(!$table_exist){
            core_go_to(BASE_URL."/table");
        }else{
            $this->setTitle(_t(1).": ".$this->arguments[0]);
        }
    }


    protected function echoContent() {
        $this->add_frontend_translation(93);
        $this->add_frontend_translation(109);
        if($this->newController){
            $this->newController->echoContent();
            return;
        }
             
        $select = NULL;
        $table = NULL;
        $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
        $page == 0 ? ($page = 1) : NOEXPR;
        $offset = ($page-1)*PAGE_SIZE_LIMIT;
        $query_link = "";
        
        if(isset($this->arguments[0]) && isset($this->arguments[1]) && isset($this->arguments[2])){
            $description = get_foreign_key_description($this->arguments[0], $this->arguments[1])->fetch(PDO::FETCH_NUM);
            $table = $description[0];
            $query = db_select($table)
                    ->condition("ID = :id", ["id"=> intval($this->arguments[2]) ]);
            $columns = $query->limit(PAGE_SIZE_LIMIT, $offset)->execute()->fetchAll(PDO::FETCH_NUM);
            $count = $query->select("", ["count(*)"])->execute()->fetch(PDO::FETCH_NUM);
            $select = ["values" => $columns];
            $select["count"] = $count[0];
            $select["skeleton"] = get_table_description($table);
            $query_link = "/$table";                
        } elseif (isset($this->arguments[0])) {
            $table = $this->arguments[0];
            $params = $_GET;
            unset($params["page"]);
            $condition_query = "";
            $index = 0;
            foreach ($params as $key => $param){
                $params[$key] = "%$param%";
                $condition_query.= ($condition_query ? "AND " : "")."$key LIKE :$key ";
                $index++;
            }
            $select["skeleton"] = get_table_description($table);
            $query = db_select($table);
            foreach ($select["skeleton"] as $column){
                if( in_array($column[1], ["longtext", "text"]) ){
                    $function = "CONCAT( SUBSTRING(`{$column[0]}`, 1, ".self::TEXT_SIZE_LIMIT."), IF(LENGTH(`{$column[0]}`)> ".self::TEXT_SIZE_LIMIT.", ' ...', '') ) AS `{$column[0]}` ";
                    $query->select_with_function([$function]);
                }else{
                    $query->select($table, [$column[0]]);
                }
            }
            $results = $query->condition($condition_query)
                    ->params($params)
                    ->limit(PAGE_SIZE_LIMIT, $offset)->execute()->fetchAll(PDO::FETCH_NUM);
            $count = db_select($table)
                    ->condition($condition_query)
                    ->params($params)
                    ->select("", ["count(*)"])->execute()->fetch(PDO::FETCH_NUM);
            $select["values"] = $results;
            $select["count"] = $count[0];
            
            $query_link = "?".http_build_query($params);
        } else {
            create_warning_message(_t(51), "alert-info");
        }
        require 'table_html.php';
        echo_tablolar($this, $select, $table, $page, $offset, $query_link);
    }
    
    

}