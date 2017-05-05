<?php
$options = explode(',', $filter['value']);

echo "<div class=\"col-sm-4\"><label class='form-label'>{$filter['label']}:</label><br>";
echo "<select class='form-control {$filter['class']}' multiple name='{$filter['name']}'>";
foreach($options as $option){
    echo "<option value='{$option}' selected='selected'>$option</option>";
}
echo "</select></div>";

