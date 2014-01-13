<?php

namespace Proyecto\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Proyecto\PrincipalBundle\Entity\User;
use Proyecto\PrincipalBundle\Entity\Autores;
use Proyecto\PrincipalBundle\Entity\Proyecto;

class DefaultController extends Controller {

	public function historiaAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('Historia', 'Breve reseña histórica de los metodos utilizados', $this);
		$secondArray = array();

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal2:historia.html.twig', $array);
	}
	public function acercaAction() {
			
		$firstArray = UtilitiesAPI::getDefaultContent('Acerca', 'Autores del sistema', $this);
		$user = UtilitiesAPI::getActiveUser($this);
		


		$autors = UtilitiesAPI::getAutors($this);
		$auxiliar = array('descripcionusuario' => stripcslashes(html_entity_decode($user -> getDescripcion())));
		$secondArray = array('autors' => $autors, 'auxiliar' => $auxiliar);


		$array = array_merge($firstArray, $secondArray);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal2:acerca.html.twig', $array);
	}

	public function listadoAction() {
		exit;
		$firstArray = UtilitiesAPI::getDefaultContent('Listado', 'Seleccione el archivo a editar', $this);
		$secondArray = array();
		
		$secondArray['objects'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Proyecto') -> findByUser( UtilitiesAPI::getActiveUser($this) );
		

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal:listado.html.twig', $array);
	}

	public function indexAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('Panel de Control', 'Bienvenido seleccione su categoría', $this);
		$secondArray = array();

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal2:index.html.twig', $array);
	}

	public function nuevoAction() {
		exit;
		$firstArray = UtilitiesAPI::getDefaultContent('Nuevo', 'Para comenzar por favor rellene el formulario', $this);
		$secondArray = array('object'=>null);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal:ingreso.html.twig', $array);
	}

	public function procesaObjetoAction() {
		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES
		$tipo = $post -> get("tipo");
		
		//Saber si se actualiza o si se crea
		$nombre = trim(strtolower($post -> get("nombre")));
		$langelier = (($post -> get("langelier") != 'true') ? 0 : 1);
		$rysnar = (($post -> get("rysnar") != 'true') ? 0 : 1);
		$puckoris = (($post -> get("puckoris") != 'true') ? 0 : 1);
		$informacion = trim(strtolower($post -> get("informacion")));
		$autoguardado = (($post -> get("autoguardado") != 'true') ? 0 : 1);
		$ph = floatval($post -> get("ph"));		
		$tds = floatval($post -> get("tds"));
		$t = floatval($post -> get("t"));
		$ca2 = floatval($post -> get("ca2"));				
		$tds = floatval($post -> get("tds"));
		$alcalinidad = floatval($post -> get("alcalinidad"));

		$id = intval($post -> get("id"));	
		$id = UtilitiesAPI::saveProject( $tipo, $id, $nombre, $langelier, $rysnar, $puckoris, $informacion, $autoguardado, $ph, $tds, $t, $ca2, $tds, $alcalinidad, $this);
		
		$respuesta = new response(json_encode(array('estado' => true, 'id' => $id )));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

	public function eliminarAction() {
		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES
		$id = $post -> get("id");
		UtilitiesAPI::removeProject($id,$this);
		$respuesta = new response(json_encode(array('estado' => true )));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

	public function editarAction($id) {
		$object = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Proyecto') -> find( $id );

		$firstArray = UtilitiesAPI::getDefaultContent('Editar', 'Para actualizar por favor edite los campos en el formulario', $this);
		$secondArray = array('object'=>$object);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal:ingreso.html.twig', $array);
		
	}
	public function verAction($id) {
		$object = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Proyecto') -> find( $id );
		
		$firstArray = UtilitiesAPI::getDefaultContent('Ver', 'Análisis de los resultados', $this);
		$secondArray = UtilitiesAPI::getArrayResults($object,$this);
		
		//print_r($secondArray);
		//exit;
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Principal:ver.html.twig', $array);
	}

}
