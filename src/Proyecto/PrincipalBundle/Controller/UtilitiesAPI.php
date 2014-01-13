<?php

namespace Proyecto\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Proyecto\PrincipalBundle\Entity\User;
use Proyecto\PrincipalBundle\Entity\Autores;
use Proyecto\PrincipalBundle\Entity\Sistema;
use Proyecto\PrincipalBundle\Entity\Proyecto;

class UtilitiesAPI extends Controller {
	
	public static function getPhs($object,$class){
	
		$a = (log10($object->getTds()) -1) /10;
		$b = -13.12 * log10 ($object->getT() +273) + 34.55;
		$c = log10( $object->getCa2() ) - 0.4; 
		$d = log10( $object->getAlcalinidad() );
	
		$phs = 9.3 + $a + $b - ( $c + $d );
		
		return $phs;
	
	}
	public static function getLangelier($object,$phs,$class){
	
		$valor  = $object->getPh() - $phs;
		
		$array = array('valor'=>$valor,'parametro'=>'','corrosion'=>'');
		//$valor = 0.3;
		
		//if( $valor >  -2.0 && $valor < -0.5){
		if( $valor < -0.5){
			$array['parametro'] = "-2,0<LSI<-0,5";
			$array['corrosion'] = "Corrosión severa";
		}
		else if($valor >= -0.5 && $valor < 0){
			$array['parametro'] = '-0,5<=LSI<0';
			$array['corrosion'] = 'Corrosión leve pero sin formación de incrustaciones';
		}
		else if($valor == 0){
			$array['parametro'] = 'LSI= 0,0';
			$array['corrosion'] = 'Equilibrada pero posible corrosión leve';
		}
		else if($valor > 0 && $valor <= 0.5){
			$array['parametro'] = '0,0<LSI<=0,5';
			$array['corrosion'] = 'Formación leve de incrustaciones y corrosiva';
		}
		//else if($valor > 0.5 && $valor <2){
		else if($valor > 0.5 ){
			$array['parametro'] = '0,5<LSI<2';
			$array['corrosion'] = 'Formación de incrustaciones pero no corrosiva';
		}

		return $array;
	}
	public static function getRyznar($object,$phs,$class){
	
		$valor  = (2*$phs) - $object->getPh();
		
		$array = array('valor'=>$valor,'parametro'=>'','corrosion'=>'');

		if( $valor <= 5){
			$array['parametro'] = "4< RSI≤ 5";
			$array['corrosion'] = "Muy incrustante";
		}
		else if($valor > 5 && $valor <= 6 ){
			$array['parametro'] = '5< RSI ≤ 6';
			$array['corrosion'] = 'Débilmente incrustante';
		}
		else if($valor > 6 && $valor <= 7 ){
			$array['parametro'] = '6< RSI ≤ 7';
			$array['corrosion'] = 'En equilibrio';
		}		
		else if($valor > 7 && $valor <= 7.5 ){
			$array['parametro'] = '7< RSI ≤ 7,5';
			$array['corrosion'] = 'Corrosiva';
		}		
		else if($valor > 7.5 && $valor <= 9 ){
			$array['parametro'] = '7,5 < RSI ≤ 9';
			$array['corrosion'] = 'Fuertemente corrosiva';
		}
		else if($valor > 9 ){
			$array['parametro'] = '9< RSI';
			$array['corrosion'] = 'Muy fuertemente corrosiva';
		}		

		return $array;
	}
	public static function getPuckorius($object,$phs,$class){
	
		$valor  = (2*$phs) - (1.465 * log10( $object->getAlcalinidad() ) + 4.54);
		
		$array = array('valor'=>$valor,'parametro'=>'','corrosion'=>'');

		if( $valor < 4.5){
			$array['parametro'] = "PSI < 4,5";
			$array['corrosion'] = "Tendencia a la incrustación";
		}
		else if( $valor >= 4.5 && $valor <= 6.5 ){ //INCIDENCIA
			$array['parametro'] = '4,5 <= PSI <= 6,5';
			$array['corrosion'] = 'Rango óptimo (No hay corrosión)';
		}
		else if( $valor > 6.5 ){
			$array['parametro'] = 'PSI > 6,5';
			$array['corrosion'] = 'Tendencia a la corrosión';
		}			

		return $array;
	}
	public static function getArrayResults($object,$class){
		
		$array = array('object' => $object );
	
		$phs = UtilitiesAPI::getPhs($object,$class);
		
		if($object->getLangelier()==1)
			$array['langelier'] = UtilitiesAPI::getLangelier($object,$phs,$class);
		
		if($object->getRysnar()==1)
			$array['ryznar']    = UtilitiesAPI::getRyznar($object,$phs,$class);
		
		if($object->getPuckorius()==1)
			$array['puckorius'] = UtilitiesAPI::getPuckorius($object,$phs,$class);
		
		return $array;
	}
	public static function removeProject($id,$class){
			
		$object = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Proyecto') -> find($id);
		$em = $class -> getDoctrine() -> getManager();
		$em->remove($object);
		$em->flush();
	}

	public static function saveProject( $tipo, $id, $nombre, $langelier, $rysnar, $puckoris, $informacion, $autoguardado, $ph, $tds, $t, $ca2, $tds, $alcalinidad, $class) {

		$object = null;
		
		if ($tipo == 0) {
			$object = new Proyecto();
			$object -> setFecha(new \DateTime());
		} else {
			$object = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Proyecto') -> find($id );
			if (!$object) {
				throw $class -> createNotFoundException('No se encontro el proyecto ');
			}
		}

		
		$object -> setNombre($nombre);
		$object -> setLangelier($langelier);
		$object -> setRysnar($rysnar);
		$object -> setPuckorius($puckoris);
		$object -> setInformacion($informacion);
		$object -> setAutoguardado($autoguardado);
		$object -> setUser(UtilitiesAPI::getActiveUser($class));
		$object -> setPh($ph);
		$object -> setTds($tds);
		$object -> setT($t);
		$object -> setCa2($ca2);
		$object -> setAlcalinidad($alcalinidad);

		$em = $class -> getDoctrine() -> getManager();
		$em -> persist($object);
		$em -> flush();
		
		return $object->getId();
		
	}

	
	public static function getDefaultContent($item,$info,$class){
		
		$parameters = UtilitiesAPI::getParameters($class);
		$menu = UtilitiesAPI::getMenu($item,$class);
		$user = UtilitiesAPI::getActiveUser($class);
		$notifications = UtilitiesAPI::getNotifications($user);
		$usuario = $user;// UtilidadesAPI::usuarioActual($this);
		if($usuario!=NULL)$usuario = ucfirst($usuario->getNombre()).' '.ucfirst($usuario->getApellido());
		else $usuario = '';
		$datos = array('usuario' => $usuario, 'fecha' => UtilitiesAPI::obtenerFechaSistema($class));
		
		$array = array('parameters' => $parameters,'menu' => $menu,'user' => $user, 
		               'info' => $info, 'notifications' => $notifications,
		               'datos' => $datos
					   );
		
		return $array;
	}

	public static function getAutors($class) {
		$autors = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Autores') -> findAll();
		$users = array();
		for ($i = 0; $i < count($autors); $i++) {
			$users[$i] = $autors[$i] -> getUser();
			
		}

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $users;
	}

	public static function getParameters($class) {
		$parameters = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Sistema') -> find(1);

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $parameters;
	}

	public static function getMenu($seccion, $this) {
		$menu = array('seccion' => $seccion);
		// = $this->getDoctrine()->getRepository('ProyectoPrincipalBundle:Sistema')->find(1);

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $menu;
	}

	public static function getActiveUser($class) {

		$user = $class -> getUser();

		if ($user != NULL && false === $class -> get('security.context') -> isGranted('ROLE_ADMIN')) {
			$user = null;
		}

		return $user;
	}

	public static function getNotifications($user) {

		$notifications = null;

		if ($user != NULL) {
			$notifications = array();
			$notifications[0]['texto'] = 'Espacio reducido';
			$notifications[0]['numero'] = '40%';
		}

		return $notifications;
	}

	public static function procesaUsuario($tipo, $nombre, $apellido, $nombreusuario, $contrasenia, $email, $descripcion, $class) {

		$factory = $class -> get('security.encoder_factory');
		$user = null;

		if ($tipo == 0) {
			$user = new User();
			$encoder = $factory -> getEncoder($user);
			$password = $encoder -> encodePassword($contrasenia, $user -> getSalt());
			$user -> setPassword($password);

		} else {
			$user = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:User') -> find( UtilitiesAPI::getActiveUser($class) -> getId());
			if (!$user) {
				throw $class -> createNotFoundException('No se encontro el usuario ' . UtilitiesAPI::getActiveUser($class) -> getId());
			}
			if (strlen($contrasenia) >= 8) {
				$encoder = $factory -> getEncoder($user);
				$password = $encoder -> encodePassword($contrasenia, $user -> getSalt());
				$user -> setPassword($password);
			}
		}

		$user -> setNombre($nombre);
		$user -> setApellido($apellido);
		$user -> setUsername($nombreusuario);
		$user -> setEmail($email);
		$user -> setDescripcion($descripcion);

		$em = $class -> getDoctrine() -> getManager();
		$em -> persist($user);
		$em -> flush();
	}



	 public static function obtenerFechaSistema($class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $anio = $hoy['year'];
	 $mes = intval($hoy['mon']) - 1;
	 $dia = $hoy['mday'];
	 $hora = $hoy['hours'];
	 $minuto = $hoy['minutes'];

	 $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
	 $dsemana = $hoy['wday'];

	 $fecha = $dias[$dsemana] . ", " . $dia . " de " . $meses[$mes] . ' de ' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }
	/*
	 public static function obtenerFechaCastellanizada($class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $anio = $hoy['year'];
	 $mes = intval($hoy['mon']) - 1;
	 $dia = $hoy['mday'];
	 $hora = $hoy['hours'];
	 $minuto = $hoy['minutes'];
	 $fecha = $dia . " de " . $meses[$mes] . ' del ' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada2($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $arreglo[0] . " de " . $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada3($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada4($fechaOriginal, $class) {

	 $arreglo = explode("/", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $arreglo[0] . " de " . $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerNombreMes($fecha, $class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

	 $mes = intval($fecha['mon']) - 1;
	 return $mes;
	 }

	 public static function obtenerFechaNormal($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $dia . "/" . $mes . '/' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaNormal2($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $dia . "-" . $mes . '-' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaNormal3($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $anio . "-" . $mes . '-' . $dia;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerMesYAnio($class) {
	 $hoy = getdate();
	 return array($hoy['year'], $hoy['mon']);
	 }

	 public static function convertirFechaNormal($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirFechaNormal3($fechaOriginal, $class) {
	 $arreglo = explode("/", $fechaOriginal);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirFechaNormal2($fechaOriginal, $class) {
	 $fechaOriginal = trim($fechaOriginal);
	 $arreglo1 = explode(" ", $fechaOriginal);
	 $arreglo = explode("-", $arreglo1[0]);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirAFechaNormal($fechaOriginal, $class) {

	 $fechaOriginal = new \DateTime($fechaOriginal);
	 return date_format($fechaOriginal, 'd/m/Y'); ;
	 }

	 public static function convertirAFormatoSQL($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 if ($arreglo[1] < 10)
	 $arreglo[1] = '0' . $arreglo[1];
	 if ($arreglo[0] < 10)
	 $arreglo[0] = '0' . $arreglo[0];
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0] . ' 00:00:00';

	 return $fecha;

	 }

	 public static function obtenerFechasFormatoSQL($anio, $mes, $class) {

	 if ($mes < 10)
	 $mes = '0' . $mes;
	 $dia = '01';

	 $fechaInicial = $anio . '-' . $mes . '-' . $dia . ' 00:00:00';
	 $dia = '31';
	 $fechaFinal = $anio . '-' . $mes . '-' . $dia . ' 00:00:00';

	 $arreglo = array($fechaInicial, $fechaFinal);

	 return $arreglo;

	 }

	 public static function convertirAFormatoSQL2($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 if ($arreglo[1] < 10)
	 $arreglo[1] = '0' . $arreglo[1];
	 if ($arreglo[0] < 10)
	 $arreglo[0] = '0' . $arreglo[0];
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0];

	 return $fecha;

	 }

	 public static function convertirAFormatoSQL3($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);

	 $fecha = $arreglo[2] . '/' . $arreglo[1] . '/' . $arreglo[0];

	 return $fecha;

	 }

	 public static function convertirAFormatoSQL4($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0] . ' 00:00:00';

	 return $fecha;

	 }

	 public static function primerDiaMes($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-01 00:00:00';

	 return $fecha;

	 }

	 public static function primerDiaMesSiguiente($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $mes = intval($arreglo[1]);
	 $anio = intval($arreglo[2]);

	 if ($mes == 12) {
	 $mes = "01";
	 $anio++;
	 } else {
	 $mes++;
	 if ($mes < 9)
	 $mes = "0" . $mes;
	 }

	 $fecha = $anio . '-' . $mes . '-01 00:00:00';

	 return $fecha;

	 }

	 public static function sumarTiempo($fechaOriginal, $dia, $mes, $anio, $class) {

	 $arreglo = explode("-", $fechaOriginal);

	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 $fecha -> setTime(0, 0, 0);
	 $periodo = 'P' . $anio . 'Y' . $mes . 'M' . $dia . 'D';
	 $fecha -> add(new \DateInterval($periodo));

	 $fecha = date_format($fecha, 'Y-m-d H:i:s'); ;
	 return $fecha;

	 }
	 */
}
