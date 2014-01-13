/**
 * @author Venechi
 */
$(".imprimir").live("click", function() {
	$('.contenedor2').printElement();
});



$(".cancelarestudio").live("click", function() {
	
	var id = $('#idestudio').val();
	var trabajo = $('#trabajo').val();
	var data = "id=" + id ;
	if(trabajo=='nuevo'){
		$.post(direccionEliminarEstudio, data, function(respuesta) {
				document.location.href = direccionInicio;
		});
	}
	else{
		document.location.href = direccionInicio;
	}
});

$(".guardarcomo").live("click", function() {
	var nombrestudio = $('#nombreestudio').val();
	var id = $('#idestudio').val();
	var nombrestudio = prompt("Ingrese el nombre del estudio", nombrestudio);
	if(nombrestudio != null && nombrestudio != "") {
		var data = "id=" + id + "&nombrestudio=" + nombrestudio;
		$.post(direccionGuardarComo, data, function(respuesta) {

		});
	}
});
$(".eliminar").live("click", function() {
	$(this).css('display', 'none');
	$(this).next().css('display', 'block');
	var id = $(this).attr('codigo');
	var actual = $(this);

	var data = "id=" + id;
	$.post(direccionEliminarEstudio, data, function(respuesta) {
		actual.parent().parent().remove();
	});
});
$(".editar").live("click", function() {
	$(this).css('display', 'none');
	$(this).next().css('display', 'block');
	var id = $(this).attr('codigo');
	document.location.href = direccionEdicion1 + '/' + id;

});
$("#nuevoGuardar").live("click", function() {

	if(!validaTodosLosCampos($("input[type=text],input[type=password],textarea").not('.opcional')))
		return false;

	var nombre = $("#nombre").val();

	$('#contenidoregistro').css('display', 'none');
	$("#loader").css('display', 'block');

	var data = 'nombre=' + nombre + "&autoguardado=" + $("#autoguardado:checked").length + "&idestudio=" + $('#idestudio').val() + "&fases=";
	var j = 1;
	$.each($('select').val(), function(indice, valor) {
		data += valor;
		if(j < $('select').val().length)
			data += ',';
		j++;
	});
	$(this).attr('id', 'nuevoSinGuardar');

	$.post(direccionNuevoGuardar, data, function(respuesta) {
		$('.contenedor').empty();
		$('.contenedor').append(respuesta.html);

	});
});

$("#registro").live("click", function() {

	if(!validaTodosLosCampos($("input[type=text],input[type=password],textarea").not('.opcional')))
		return false;

	if(!validaContrasenias($("input[name=contrasenia1]"), $("input[name=contrasenia2]")))
		return false;

	$('#contenidoregistro').css('display', 'none');
	$("#loader").css('display', 'block');

	var data = 'tipo=registro';
	$.each($("input[type=text],input[type=password]"), function(indice, valor) {
		var auxiliar = $.trim($(valor).val());
		var id = $(valor).attr('id');
		data += '&' + id + '=' + auxiliar;

	});

	$.post(direccionGuardarUsuario, data, function(respuesta) {

		if(respuesta.estado == true) {
			jAlert(respuesta.texto, 'Información');
			document.location.href = direccionInicio;
		} else {
			jAlert(respuesta.texto, 'Error');
			$("#loader").css('display', 'none');
			$('#contenidoregistro').css('display', 'block');
		}

	});

	//console.log(data);
});

$(".regresar").live("click", function() {
	var fase = $(this).attr('fase');
	$('#cuadrof' + fase).css('display', 'none');
	$('#cuadrof' + fase).removeClass('usado');
	var aux = "cuadrof" + fase;
	var aux2 = "";
	var prueba = 0;
	var resultado = '';
	$.each($('.visible'), function(indice, valor) {
		var id = $(valor).attr('id');
		if(id == aux) {
			prueba = 1;
			resultado = aux2.substr(aux2.length - 1, 1);
			prueba = 0;
		}
		aux2 = id;
	});

	$('#cuadrof' + (resultado)).css('display', 'block');
	$('#contenidof' + (resultado)).css('display', 'block');
});
$(".faseanterior").live("click", function() {

	var dato = $('.usado').last().attr('id').split("cuadrof");
	var ultimo = dato[1];

	$('.contenedor2').remove();
	$('.botonescontenedor2').remove();
	$('.contenedor').css('display', 'block');
	$('#cuadrof' + ultimo).css('display', 'block');
	$('#contenidof' + ultimo).css('display', 'block');

});
$(".siguientefase").live("click", function() {

	var dato = $(".visible:not(.usado)").first().attr('id').split("cuadrof");
	var ultimo = dato[1];
	$('.contenedor2').remove();
	$('.botonescontenedor2').remove();
	$('.contenedor').css('display', 'block');
	$('#cuadrof' + ultimo).css('display', 'block');
	$('#contenidof' + ultimo).css('display', 'block');

});
$(".cambiofase").live("click", function() {
	var fase = $(this).attr('fase') - 1;
	var id = $('#idestudio').val();
	var idfase = $("#idf" + fase).val();
	var tipo = $(this).attr('tipo');
	var ultimo = $('#ultimo').val();
	var actual = $(this);

	if(!validaMinimoDosCampos($("#cuadrof" + fase).find("input[type=text],textarea").not('.opcional')))
		return false;
	if(!validaCamposNumericos($("#cuadrof" + fase).find(".numero")))
		return false;
	if(!validaCamposFlujos($("#cuadrof" + fase).find('.flujos')))
		return false;

	if(tipo == 'verresultado') {
		$(this).next().removeClass('cambiofase');
	} else {
		$(this).prev().removeClass('cambiofase');
	}
	$(this).removeClass('cambiofase');

	var data = 'id=' + id + '&idfase=' + idfase + '&numeroFase=' + fase + '&ultimo=' + ultimo + '&tipo=' + tipo;
	$.each($("#cuadrof" + fase).find("input[type=text],textarea"), function(indice, valor) {
		var auxiliar = $(valor).val();
		var id = $(valor).attr('id');
		data += '&' + id + '=' + auxiliar;

	});
	data += '&unidades=';
	var tamanio = $("#cuadrof" + fase).find("select").length;
   
	$.each($("#cuadrof" + fase).find("select"), function(indice, valor) {
		var identificador ="";
		var auxiliar ="";
		if($(valor).hasClass('especial')){
			identificador = $(valor).attr('id');
		}
		else{
			identificador = $(valor).parent().prev().find('input').attr('id');
			
		}
		
		auxiliar = $(valor).val();
		data += identificador +'-' + auxiliar;
		
		if(indice < (tamanio - 1))data += ',';

	});	
	//console.log(data);
	$('#contenidof' + fase).css('display', 'none');
	$("#loaderf" + fase).css('display', 'block');

	$.post(direccionGuardarFase, data, function(respuesta) {
		
		$("#idf" + fase).val(respuesta.idfase);
		
		if(tipo == 'avanzar') {
			if(fase == ultimo) {
				$("#loaderf" + fase).css('display', 'none');
				$("#cuadrof" + fase).css('display', 'none');
				$("#cuadrof" + fase).addClass('usado');
				$('.contenedor2').remove();
				$('.botonescontenedor2').remove();
				$('.contenedor').after(respuesta.auxiliar);
				tieneBotonSiguiente();
				$('.contenedor').css('display', 'none');
				renuevaClase(tipo, actual,fase);
				return false;
			}
			if((fase + 1) != 9) {

				$("#loaderf" + fase).css('display', 'none');
				$("#cuadrof" + fase).css('display', 'none');
				$("#cuadrof" + fase).addClass('usado');

				var aux = "cuadrof" + fase;
				var prueba = 0;
				var resultado = '';
				$.each($('.visible'), function(indice, valor) {
					var id = $(valor).attr('id');
					if(id == aux)
						prueba = 1;
					else {
						if(prueba == 1) {
							resultado = id.substr(id.length - 1, 1);
							prueba = 0;
						}

					}

				});

				$("#cuadrof" + resultado).css('display', 'block');
				
				$("#idestudio").val(respuesta.id);
				renuevaClase(tipo, actual,fase);

			} else {
				
				$("#loaderf" + fase).css('display', 'none');
				$("#cuadrof" + fase).css('display', 'none');
				$("#cuadrof" + fase).addClass('usado');
				$('.contenedor2').remove();
				$('.botonescontenedor2').remove();
				$('.contenedor').after(respuesta.auxiliar);
				tieneBotonSiguiente();
				$('.contenedor').css('display', 'none');
				renuevaClase(tipo, actual,fase);
				return false;

			}
		} else {//IR A RESULTADOS

			
			$("#loaderf" + fase).css('display', 'none');
			$("#cuadrof" + fase).css('display', 'none');
			$("#cuadrof" + fase).addClass('usado');
			$('.contenedor2').remove();
			$('.botonescontenedor2').remove();
			$('.contenedor').after(respuesta.auxiliar);
			tieneBotonSiguiente();
			$('.contenedor').css('display', 'none');
			
			renuevaClase(tipo, actual, fase);
			
			return false;
		}

	});
});
function renuevaClase(tipo, actual,fase) {
	//console.log('La fase es '+fase+' y el ultimo fue = '+$('#ultimo').val());
	if(tipo == 'verresultado' ) {
		if( $('#ultimo').val() != fase) actual.next().addClass('cambiofase');
	} else {
		actual.prev().addClass('cambiofase');
	}
	actual.addClass('cambiofase');
}
function tieneBotonSiguiente(){
	
	if($(".visible:not(.usado)").length ==0){
		 $("#siguientef").css('display','none');
	}
	
}

$("#guardarreporte").live("click", function() {
	var tipoReporte = $('[name=tipoReporte]').val();

	var pkReporte = $('[name=pkReporte]').val();

	//insertaId($("input[type=text],textarea,select"),tipoReporte);return false;

	if(!validaCamposLlenos($("input[type=text],textarea").not('.opcional')))
		return false;
	if(!validaCamposNumericos($('.numero')))
		return false;
	if(!validaCamposFecha($('.fecha')))
		return false;
	if(!validaCamposSelect($('select')))
		return false;

	var data = 'tipoReporte=' + tipoReporte + '&pkReporte=' + pkReporte;
	$.each($("input[type=text],textarea,select"), function(indice, valor) {
		var auxiliar = $(valor).val();
		var id = $(valor).attr('id');
		data += '&' + id + '=' + auxiliar;

	});
	$('.formularioagregar').empty();
	$(".loader").css('display', 'block');

	$.post(direccionGuardarReporte, data, function(respuesta) {
		$(".loader").css('display', 'none');
		$('.formulario').append('</br></br></br><span class="Subtitulo-Normal">' + respuesta.mensaje + '</span>');
	});
});