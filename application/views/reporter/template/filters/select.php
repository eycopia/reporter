<?php
    $options = explode(',', $filter['value']);

    echo "<div class=\"col-sm-2\"><label class='form-label'>{$filter['label']}:</label><br>";
    echo "<select class='form-control {$filter['class']}'  name='{$filter['name']}'>";
    echo "<option value='' selected='selected'>Todos</option>";
    foreach($options as $option){
        echo "<option value='{$option}'>$option</option>";
    }
    echo "</select></div>";
