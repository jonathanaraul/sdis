/**
 * @author Jonathan Araul
 *
 */


function creaScriptBD(base){
	
	
	var elementos = $("input[type=text],textarea,select");
	
	var script= 'CREATE TABLE '+base+' ( id'+base+' int(11) NOT NULL AUTO_INCREMENT,idestudio INT NOT NULL,'

	$.each(elementos, function(indice, valor) {
		
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().find('label').html());
		

		campo=$.trim(campo.replace("ñ",'ni'));
		campo=$.trim(campo.replace(":",''));
		campo=$.trim(campo.replace(/<[^>]+>/g,''));
		campo=$.trim(campo.replace("+",''));
		campo=$.trim(campo.replace("-",''));
		campo=$.trim(campo.replace("(",''));
		campo=$.trim(campo.replace(")",''));		
		campo=$.trim(campo.replace("%",''));
		campo=$.trim(campo.replace("á",'a'));
		campo=$.trim(campo.replace("é",'e'));
		campo=$.trim(campo.replace("í",'i'));
		campo=$.trim(campo.replace("ó",'o'));
		campo=$.trim(campo.replace("ú",'u'));
		
		campo=$.trim(campo.replace("°",''));
		campo=$.trim(campo.replace(/\W/g,' '));				
		campo=$.trim(campo.replace(/\s/g,''));
		campo=$.trim(campo.replace(/\d/g,''));
		campo=campo.toLowerCase();
		
			script += campo+' float NOT NULL,';
		


		
	});

	script += 'PRIMARY KEY (id'+base+'), INDEX (  idestudio ),FOREIGN KEY (  idestudio ) REFERENCES  optidrill.estudio (id) ON DELETE RESTRICT ON UPDATE RESTRICT );';
	console.log(script);
	return false;
}
function insertaId(prenombre){
	

	var elementos =$("input[type=text],textarea,select");
	

	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().find('label').html());
		

		campo=$.trim(campo.replace("ñ",'ni'));
		campo=$.trim(campo.replace(":",''));
		campo=$.trim(campo.replace(/<[^>]+>/g,''));
		campo=$.trim(campo.replace("+",''));
		campo=$.trim(campo.replace("-",''));
		campo=$.trim(campo.replace("(",''));
		campo=$.trim(campo.replace(")",''));		
		campo=$.trim(campo.replace("%",''));
		campo=$.trim(campo.replace("á",'a'));
		campo=$.trim(campo.replace("é",'e'));
		campo=$.trim(campo.replace("í",'i'));
		campo=$.trim(campo.replace("ó",'o'));
		campo=$.trim(campo.replace("ú",'u'));
		
		campo=$.trim(campo.replace("°",''));
		campo=$.trim(campo.replace(/\W/g,' '));				
		campo=$.trim(campo.replace(/\s/g,''));
		campo=$.trim(campo.replace(/\d/g,''));
		campo=campo.toLowerCase();
		
		/*
		if(prenombre != undefined){
			campo = prenombre+'_'+campo;
		}		
		*/
		$(valor).attr('id',campo);
		
	});
	return false;
}
function validaTodosLosCampos(elementos) {
	var validacion = true;
	var camposLlenos = 0;
	$.each(elementos, function(indice, valor) {
		var auxiliar = $.trim($(valor).val());
		var campo = $.trim($(valor).parent().prev().find('label').html());
		campo=$.trim(campo.replace(":",''));
		
		if(auxiliar == '') {
			jAlert('Debe llenar el campo ' + campo, 'Error');
			$(valor).css('border', '1px solid red');
			validacion = false;
			return false;
		} else {
			$(valor).css('border', '1px solid #929292');
		}
		
		
	});
	
	
	return validacion;
}
function validaContrasenias(contrasenia1, contrasenia2) {

	if(contrasenia1.val().length <= 5 || contrasenia2.val().length <= 5) {
		jAlert('Las contraseñas deben contener por lo menos 6 caracteres.', 'Error');
		contrasenia1.css('border', '1px solid red');
		contrasenia2.css('border', '1px solid red');
		return false;
	}
	if(contrasenia1.val() != contrasenia2.val()) {
		jAlert('Las contraseñas deben ser iguales.', 'Error');
		contrasenia1.css('border', '1px solid red');
		contrasenia2.css('border', '1px solid red');
		return false;
	}
	return true;
}
function validaMinimoDosCampos(elementos) {
	var validacion = true;
	var camposLlenos = 0;
	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().find('label').html());
		campo=$.trim(campo.replace(":",''));
		
		if(auxiliar != '') {
			camposLlenos++;
		} 
	});
	
	if(camposLlenos < 2) {
			jAlert('Debe introducir por lo menos dos datos al sistema.','Error');
			validacion = false;
			return false;
		} 
	
	return validacion;
}
function validaCamposNumericos(elementos) {
	var validacion = true;

	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().find('label').html());
		campo=$.trim(campo.replace(":",''));
		
		if(!isNumber(auxiliar) && auxiliar != ''){
			jAlert('El valor del campo ' + campo + ' debe ser numerico', 'Error');
			$(valor).css('border', '1px solid red');
			validacion = false;
			return false;
		} else {
			$(valor).css('border', '1px solid #929292');
		}
	});
	return validacion;
}
function validaCamposFlujos(elementos) {
	var validacion = true;

	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().find('label').html());
		campo=$.trim(campo.replace(":",''));
		

		var n=auxiliar.split(",");
		
		for (var i=0; i < n.length; i++) {
			
		if(!isNumber(n[i]) && n[i] != '' ){
			jAlert('El valor del campo ' + campo + ' debe cumplir el formato indicado', 'Error');
			$(valor).css('border', '1px solid red');
			validacion = false;
			return false;
		} else {
			$(valor).css('border', '1px solid #929292');
		}
		};
		
	});
	return validacion;
}
function validaCamposFecha(elementos) {
	var validacion = true;

	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().html());
		if(!checkFecha(auxiliar) ){
			jAlert('El valor del campo ' + campo + ' debe ser de tipo fecha, ej: 1-1-2012', 'Error');
			$(valor).css('border', '1px solid red');
			validacion = false;
			return false;
		} else {
			$(valor).css('border', '1px solid #929292');
		}
	});
	return validacion;
}
function validaCamposSelect(elementos) {
	var validacion = true;

	$.each(elementos, function(indice, valor) {
		var auxiliar = $(valor).val();
		var campo = $.trim($(valor).parent().prev().html());
		if(auxiliar == '0' ){
			jAlert('Debe seleccionar un tipo de ' + campo, 'Error');
			$(valor).css('border', '1px solid red');
			validacion = false;
			return false;
		} else {
			$(valor).css('border', '1px solid #929292');
		}
	});
	return validacion;
}
function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function checkEmail(email) {
	console.log('Email '+email);
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!filter.test(email)) {
		return false;
	}
	return true;
}

function checkCedula(cedula) {
	if((parseInt(cedula.indexOf('.'))) >= 0) {
		return false;
	} else {
		if(!isNumber(cedula)) {
			return false;
		}
		else{
			return true;
		}
	}
}
function checkFecha(fecha) {
	var arreglo = fecha.split("-");
	
	if(arreglo.length !=3) return false;
	else return true;
}

