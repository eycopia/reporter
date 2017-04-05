## Reporter
Crea reportes de manera rápida y fácil

### Convierte una sentencia como esta:
```
select f.title, f.rental_rate, f.special_features, 
group_concat(concat(a.first_name, ' ', a.last_name) SEPARATOR ', ') as actors,
f.release_year
from sakila.film as f
left join sakila.film_actor as fa on f.film_id = fa.film_id
left join sakila.actor as a on a.actor_id = fa.actor_id
where f.rental_rate between {minimo} and {maximo}
group by f.film_id     
```

### En un reporte como este:
<img src='https://github.com/eycopia/reporter/blob/master/docs/img/home.png?raw=true' alt='reporte autogenerado'>

### Instalación

<strong>Opción Uno: </strong> Sólo tienes que descargar los archivos de este repositorio, colocarlos en 
la carpeta correspondiente. Luego debes restaura el archivo <b>ReporterExample.sql</b> 
ubicado dentro de la carpeta <b>docs</b>.

<strong>Opción Dos: </strong> Descarga el proyecto funcional, restaura la base de datos  y edita el archivo config.php
<b><a href='https://drive.google.com/open?id=0B3Z09dwANdmuanAtTFpqUUNnQm8'>Descargar</a></b>

### Únete
Siéntete libre de abrir un Issue, enviar Pull Request y compartir el proyecto.