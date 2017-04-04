<?php foreach ($projects as $project) {
	echo "<div class='col-sm-3'><a href='".base_url('project/'.url_title($project->slug)).
        "'><div class='alert btn-primary'>$project->name</div></a></div>";
} ?>
