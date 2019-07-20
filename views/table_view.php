<?php

function echo_table(array $table_headers, array $table_content, string $prepend_element = "", string $appent_element = "", bool $print_line_number = false ) { ?>
<table class="content" id="result_table">
    <thead>
        <tr class="head">
        <?php
        echo $prepend_element ? "<td></td>" : "";
        echo $print_line_number ? "<td>#</td>" : "";
        foreach ($table_headers as $header) {
            echo "<td>$header</td>";
        } 
        echo $appent_element ? "<td></td>" : "";
        ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($table_content as $line_num => $content) {
            echo "<tr>";
            echo $prepend_element ? "<td>$prepend_element</td>": "";
            echo $print_line_number ? "<td>".($line_num+1)."</td>" : "";
            foreach ($content as $value) {
                echo $value !== NULL && $value !== "" ? "<td>$value</td>" : "<td>N/A</td>";
            }
            echo $appent_element ? "<td>$appent_element</td>": "";
            echo "</tr>";
        } ?>
    </tbody>
</table>
<?php }
