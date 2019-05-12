function init(){
   CKEDITOR.replace( 'details');
   
    el = document.getElementById("sql");
    text = el.innerHTML;
    editor = ace.edit(el);
    editor.session.setValue(text);
    editor.setTheme("ace/theme/eclipse");
    editor.getSession().setMode("ace/mode/sql");

    var sqlInputsVar = [];

    /* Configuracion de las variables en el sql */
    /*----- Crea el select de tipos de datos----*/
    var $options ="";
    varTypes.forEach(function(e){
        $options += "<option  value='"+e.id+"'>"+e.name+"</option>";
    });

    $divVars = $("#sqlVars");
    $('#btnConfigureVars').on('click', function(){
        $divVars.empty();
        sqlInputsVar = [];
        var pattern = /{(\w+)}/gm;
        var sql = editor.getValue();
        $("#configureVars").removeClass('hidden');
        var vars = sql.match(pattern);
        if( vars !=  null && typeof vars != "undefined"){
            vars.forEach(function(data){
                var varName = data.slice(1,-1);
                if(sqlInputsVar.indexOf(varName) == -1) {
                    sqlInputsVar.push(varName);
                    var $select = "<select class ='form-control selectVars' id='" + varName + "_select' >"
                        + $options + "</select>";
                    $divVars
                        .append("<div class='row'><div class='col-sm-3'>"
                            + "<label class='form-label'>" + varName + "</label></div>"
                            + "<div class='col-sm-3'>" + $select + "</div>"
                            + "<div class='col-sm-3'> "
                            + "<input value='' class='form-control' id='" + varName + "_default'>"
                            + "</div>");
                }
            });
        }else{
            alert('No hay variables a configurar');
        }

        return false;
    });

    $divErros = $('#errorValidation');
    $btnSave =  $("#btn-save");
    /** Se procesan los datos para ser guardados */
    $('form').on("submit", function(){
        $btnSave.attr('disabled', "disabled");
        $divErros.addClass('hidden');
        $('#textValidation').empty();
        var inputs = $(this).serializeArray();
        var columns = getColumns($(this));
        inputs.push({'columns': columns});
        inputs.push({name:"details", value: CKEDITOR.instances.details.getData()});
        inputs.push({name:"sql", value:editor.getValue()});
        inputs.push({"filters": getVars(sqlInputsVar)});
        $.ajax({
                method: "POST",
                url: this.action,
                data: {data: JSON.stringify(inputs)}
            })
            .done(function( data ) {
                $btnSave.removeAttr('disabled');
                if(data.status){
                    window.location = data.redirect_to;
                }else{
                    alert(data.msg);
                    $btnSave.removeAttr('disabled');
                    if(data.errors.trim() != ''){
                        $('#textValidation').append(data.errors);
                        $divErros.removeClass('hidden');
                    }
                }
            })
            .fail(function( data ){
                alert('There is a problem, try again later.');
                $btnSave.removeAttr('disabled');
            });
        return false;
    });

    //select emails
    $("#select_emails").select2({
        placeholder: "People select to notify",
        ajax: {
            url: app_url + "/notify/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                var results = [];
                $.each(data, function (i, v) {
                    var o = {};
                    o.id = v.idNotify;
                    o.value = v.full_name +' '+ v.email;
                    results.push(o);
                })

                return {
                    results: results,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        //escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 3,
        templateResult: formatSelect,
        templateSelection: formatSelection
    });
}

function getVars(sqlInputsVar){
    var configuredVars = [];
    if(sqlInputsVar.length > 0){
        sqlInputsVar.forEach(function(index){
            element = {};
            element.varName = index;
            element.varType = $("#"+index+"_select").val();
            element.varDefault = $("#"+index+"_default").val();
            configuredVars.push(element);
        });
    }else{
        var fieldVars = $('#sqlVars').find('select');
        if(fieldVars.length > 0){
            for(var i = 0; i < fieldVars.length; i++){
                element = {};
                var el = fieldVars[i].id.split('_');
                element.varName = el[0];
                element.varType =  fieldVars[i].value;
                element.varDefault = $("#"+el[0]+"_default").val();
                configuredVars.push(element);
            }
        }
    }
    return configuredVars;
}

$(document).ready(init);

function formatSelect (repo) {
    return repo.value;
}

function formatSelection (repo) {
    return repo.value || repo.text;
}

function getColumns(){
    $data = $('.grid_column');
    var columns = [];
    $.each($data, function(index, tr){
        columns.push({
            'db' : $(tr).find('.column_name').val(),
            'table' : $(tr).find('.alias_table').val(),
            'dt' : $(tr).find('.alias_column').val(),
            'show' :  $(tr).find('.show_column:checked').val()
        });
    });
    return columns;
}
