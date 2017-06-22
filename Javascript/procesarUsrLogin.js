
	function mostrardiv(division) 	{ 	div = document.getElementById(division);	div.style.display = "";		};
	function cerrar(division) 		{	div = document.getElementById(division);	div.style.display="none";};		
	function validarCreacionUsr() {	
		// validacion del n�mero de documento de la persona:
		var patron = /^(\d){5,15}$/;
		if (document.frmAdminUser.txtDocumento.value.search(patron)<0) {
			alert("N\u00FAmero de documento' debe ser num\u00E9rico y no inferior a 5 caracteres");
			document.frmAdminUser.txtDocumento.focus();
			return 0;
		}	
		
		// validacion del nombre de la persona:
		patron = /[A-Za-z]{3,}/;
		if (document.frmAdminUser.txtNombre.value.search(patron)<0 || document.frmAdminUser.txtNombre.value.length<6) {
			alert("'Nombre' no debe ser inferior a 6 caracteres y poseer letras");
			document.frmAdminUser.txtNombre.focus();
			return 0;
		}
		
		// validacion de correo electr�nico:
		patron = /^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
		if (document.frmAdminUser.txtEmail.value.search(patron)<0) {
			alert("'Correo Electr\u00F3nico' no posee caracteres v\u00E1lidos");
			document.frmAdminUser.txtEmail.focus();
			return 0;
		}		

		
		// validacion de contrase�as: longitud de la contrase�a
		if (document.frmAdminUser.txtPassword.value.length < 5) {
			alert("La Contrase\u00F1a no debe ser inferior a 6 caracteres");
			document.frmAdminUser.txtPassword.focus();
			return 0;
		}			
				
		// validacion de contrase�as: igualdad de caracteres entre contrase�a1 y contrase�a2
		if (document.frmAdminUser.txtPassword.value != document.frmAdminUser.txtPassword2.value) {
			alert("Ambas contrase\u00F1as deben coincidir");
			document.frmAdminUser.txtPassword.focus();
			return 0;
		}
			
		// una vez efectuadas todas las validaciones, se procede a enviar el formulario:
		document.frmAdminUser.submit();
		return 1;
	}
	
	function validarRecuperarUsr() {
		// validacion de correo electr�nico:
		patron = /^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
		if (document.frmForgetPwd.txtEmail.value.search(patron)<0) {
			alert("'Correo Electr\u00F3nico' no posee caracteres v\u00E1lidos");
			document.frmForgetPwd.txtEmail.focus();
			return 0;
		}		
		// una vez efectuadas todas las validaciones, se procede a enviar el formulario:		
		document.frmForgetPwd.submit();
		
		
	}