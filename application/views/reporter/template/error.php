<p class="alert alert-danger">Usted tiene un error de sql</p>
<p>Sql generado: <?php  echo $data['data']->queryString; ?></p>
<pre>
	<?php print_r($data['data']->errorInfo());  ?>
</pre>