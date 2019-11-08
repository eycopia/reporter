<?php 
foreach($table['buttons'] as $btn){
    $def = json_decode($btn->definition);
    $url = site_url($def->href);
    $icon = empty($def->icon) ? '' : "<span class='{$def->icon}'></span>";
    echo "<div class='col row align-items-end'>".
             "<a class='{$def->class}' href='$url' title='{$def->title}' id='{$def->id}' target='_blank'>".
             "{$icon} {$def->name}</a></div>";
}
