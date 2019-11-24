<?php 
function echo_sidebar_list(array $items, array $options){ ?>
    <div class="list-group">
    <?php
        echo_sidebar_items($items, $options);
        ?>
    </div>
<?php }

function echo_sidebar_items($items, $options){
    $icon = $options["icon"];
    $classes = $options["classes"] ? implode(" ",$options["classes"]) : "";
    foreach ($items as $item){
            $classes = $item["classes"] ? implode(" ",$item["classes"]) : "";
        ?>
        <div class="list-group-item text-left <?php echo $classes; ?>">
            <a href="<?php echo $item['url']; ?>">
                <span class="<?php echo $icon; ?>"></span><?php echo $item["label"]; ?>
            </a>
            <?php if($item["subitems"]){ ?>
            <div class="subitems">
                <?php echo_sidebar_items($item["subitems"], []);
            ?> 
            </div>
            <?php } 
                if($item["options"]){ ?>
                    <div class="dropdown pull-right">
                        <a href="#" title="<?php echo _t(17); ?>" class="dropdown-toogle" data-toggle="dropdown"/>
                            <span class="glyphicon glyphicon-option-vertical" ></span> </a>
                        <div class="dropdown-menu">
                            <?php foreach ($item["options"] as $option) {
                                echo "<a href='".$option["url"]."'/><label class='form-control dropdown-item core-control'><span class='".$option["icon"]."'></span>".$option["label"]."</label></a>";
                            } ?>
                        </div>
                    </div>
                <?php }
            ?>
        </div>
    <?php }
}