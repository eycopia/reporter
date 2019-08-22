var  activeSearch = false;
var $table = null;

var TimerTable = {
    defaultSeconds : 300,
    playing : auto_reload,

    setSeconds:  function(seconds){
        if(typeof(seconds)=='undefined'){
            seconds = this.defaultSeconds;
        }
        this.seconds =seconds ;
    },

    stop: function(){
        this.playing=false;
    },
    play: function(){
        this.playing=true;
    },

    countdown: function () {
        if(typeof(this.seconds)=='undefined'){
            this.setSeconds();
        }

        if(this.playing){
            this.seconds--;
        }
        var secs;
        if (this.seconds > 0) {
            secs=1000;
       } else {
            this.setSeconds();
            $table.ajax.reload(null, false);
            secs=100;
       }

        setTimeout(function(){
            TimerTable.countdown()
        }, secs);


    }
}

var datatableAjaxRequest = function(callback, uri, data) {
    $.post(uri, data)
        .done(function(result) {
            callback(result);
            activeSearch = false;
        })
        .fail(function(result) {
            msg = 'Ha ocurrido un error al intentar cargar los datos';
            if(result.status == 500){
                if(activeSearch){
                    msg = "La busqueda solicitada a producido un error, " +
                    "puede probar cambiando la opción de busqueda en Search By";
                }
            }
            alert(msg);
            $('#datatable_processing').css('display', 'none');
        });
};

function autoRefresh(e){
    if(TimerTable.playing){
        TimerTable.stop();
        $('#reload_report').val(0);
    }else{
        TimerTable.play();
        $('#reload_report').val(1);
    }
    TimerTable.countdown();
}

if(typeof items_per_page == 'undefined' ){
    items_per_page =  10;
}

$(document).ready(function() {
    $('[data-toggle="popover"]').popover({'trigger': 'hover', 'html':true});
    $('input[type=reset]').click();
    $(".multiple-var").select2();
    $(".multiple-object").select2();
    var type_pagination = "full_numbers";
    
    if(!active_pagination){
    	type_pagination = "simple";
    }
    $table = $('#datatable').DataTable( {
          "processing": true,
          "pagingType": type_pagination,
          "fixedHeader": {header: true},
          "order": [], //no quitar
          "serverSide": true,
          "pageLength" : items_per_page,
          "ajax": function(data, callback, settings) {
            var uri = data_url;
            data.vars = $('form').serializeArray();
            datatableAjaxRequest(callback, uri, data);
          },
          "columns": columns_datables,
          "infoCallback": function( settings, start, end, max, total, pre ) {
        	    var api = this.api();
        	    var pageInfo = api.page.info();
        	    if(active_pagination){
        	    	return 'Showing '+pageInfo.length +' records of '+ (pageInfo.recordsTotal) + ".";
        	    }else{
        	    	return 'Showing '+pageInfo.length +' records for page.';
        	    }
        	 
        	    
          }
    } );
    

    $('#auto_refresh').on('click', autoRefresh);

    //auto refresh
    if(auto_reload){
        TimerTable.countdown();
    }

    function getDataVars(){
        return dataVars;
    }

    $('#runSearch').on("click", function(){
        var $box = $('#searchBox');
        activeSearch = true;
        if($box.length > 0 ){
            var textSearch = $box.val().trim();
            var columns = $('#searchBy').val().trim().toLowerCase();
            console.log(columns);
            if(columns == 'all'){
                $table.search(textSearch).draw();
            }else{
                $table
                    .search( '' ).columns().search( '' ) //limpia el filtro anterior
                    .columns( columns )
                    .search( textSearch )
                    .draw();
            }
        }else{
            $table
                .search( '' ).columns().search( '' )
                .search( )
                .draw();
        }
        return false;
    });

    $('#showRows').on('change', function(){
        $table.page.len( $(this).val() ).draw();
    });

  //Agrega boton de descarga
      new $.fn.dataTable.Buttons( $table, {
          buttons: [
              { extend: 'excel', className: 'btn btn-default',
                text: text_button_download_view,
                exportOptions: {
                    modifier: {
                       columns: ':visible'
                    }
                } },
              { extend: 'colvis', className: 'btn btn-default', text: text_button_columns_view}
          ]
      } );

      /** Botones para descarga */
      $table.buttons().container()
          .appendTo('#action-buttons' );

      /** Seleccion de filas */
      $('#datatable tbody').on( 'click', 'tr', function (e) {
          if( e.target.className.search('btn') < 0){
            $(this).toggleClass('selected');
          }
      } );


    //Componentes
    $('.component_link').on('click', function(){
        var data = $('form').serializeArray();
        var url = this.href
            + "?vars="+JSON.stringify(data)
            + "&datatable="+JSON.stringify($table.ajax.params()) ;
        window.location = url;
        return false;
    })
} );
