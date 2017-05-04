<?php foreach ($projects as $project) {
    $base = 'project/' . (($is_pretty) ? '' : 'name/');
	echo "<div class='col-sm-3'><a href='". base_url( $base. url_title($project->slug)).
        "'><div class='alert btn-primary'>$project->name</div></a></div>";
} ?>
