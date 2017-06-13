<?php
echo "<div class=\"col-sm-2\"><label class='form-label'>{$filter['label']}:</label><br>";
echo "<select class='form-control {$filter['class']}' name='{$filter['name']}'>";
echo "<option value='' selected='selected'>Todos</option>";
foreach($filter['value']['formatted'] as $value => $option){
    echo "<option value='{$value}'>$option</option>";
}
echo "</select></div>";
