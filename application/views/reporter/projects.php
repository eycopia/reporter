<?php foreach ($projects as $project) {
    $base = 'project/' . (($is_pretty) ? '' : 'name/');
	echo "<div class='col-lg-3 col-md-4 col-xs-12 col-sm-6'>
        <a href='". base_url( $base. url_title($project->slug))."'>
        <div class='alert btn-primary'>$project->name</div></a></div>";
} ?>
