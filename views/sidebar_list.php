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
            <?php } ?>
        </div>
    <?php }
}