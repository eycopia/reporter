<?php
echo "<div class=\"col-sm-4\"><label class='form-label'>{$filter['label']}:</label><br>";
echo "<select class='form-control {$filter['class']}' multiple name='{$filter['name']}'>";
foreach($filter['value']['formatted'] as $value => $option){
    echo "<option value='{$value}' selected='selected'>$option</option>";
}
echo "</select></div>";

