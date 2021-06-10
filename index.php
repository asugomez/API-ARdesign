<?php
include_once("libs/maLibUtils.php");
include_once("libs/modele.php");

header("Access-Control-Allow-Origin: *");

$data = array("version"=>1);

// Routes : http://localhost/~asugomez/AR-design/api/

$method = $_SERVER["REQUEST_METHOD"];
debug("method",$method);

$data["success"] = false;
$data["status"] = 400; 

// https://developers.google.com/tasks/v1/reference/

// Verif autorisation : il faut un hash
// Il peut être dans le header, ou dans la chaîne de requête

$connected = false; 

if (!($hash = valider("hash"))) 
	$hash = valider("HTTP_HASH","SERVER"); 

if($hash) {
	// Il y a un hash, il doit être correct...
	if ($connectedId = hash2id($hash)) $connected = true; 
	else {
		// non connecté - peut-être est-ce POST vers /autenticate...
		$method = "error";
		$data["status"] = 403;
	}
}


if (valider("request")) {
	$requestParts = explode('/',$_REQUEST["request"]);

	debug("rewrite-request" ,$_REQUEST["request"] ); 
	debug("#parts", count($requestParts) ); 

	$entite1 = false;
	$idEntite1 = false;
	$entite2 = false; 
	$idEntite2 = false; 
	//$entite3 = false;
	//$idEntite3 = false;

	if (count($requestParts) >0) {
		$entite1 = $requestParts[0];
		debug("entite1",$entite1); 
	} 

	if (count($requestParts) >1) {	
		if (is_id($requestParts[1])) {
			$idEntite1 = intval($requestParts[1]);
			debug("idEntite1",$idEntite1); 
		} else {
			// erreur !
			$method = "error";
			$data["status"] = 400; 
		}
	}

	if (count($requestParts) >2) {
		$entite2 = $requestParts[2];
		debug("entite2",$entite2); 
	}

	if (count($requestParts) >3) {
		if (is_id($requestParts[3])) {
			$idEntite2 = intval($requestParts[3]);
			debug("idEntite2",$idEntite2); 
		} else {
			// erreur !
			$method = "error";
			$data["status"] = 400;
		}

	}  

// TODO: en cas d'erreur : changer $method pour préparer un case 'erreur'

	$action = $method; 
	if ($entite1) $action .= "_$entite1";
	if ($entite2) $action .= "_$entite2";
	//if ($entite3) $action .= "_$entite3";
 
	debug("action", $action);

	if ($action == "POST_authenticate") {
		if ($user = valider("user"))
		if ($password = valider("password")) {
			if ($hash = validerUser($user, $password)) {
				$data["hash"] = $hash;
				$data["success"] = true;
				$data["status"] = 202;
			} else {
				// connexion échouée
				$data["status"] = 401;
			}
		}
	}
	elseif ($connected)
	{
		// On connaît $connectedId
		switch ($action) {
			// GET

			case 'GET_users' :			
				if ($idEntite1) {
					// GET /AR-design/api/users/<id>
					$data["user"] = getUser($idEntite1);
					$data["success"] = true;
					$data["status"] = 200; 
				} 
				else {
					// GET /AR-design/api/users
					$data["users"] = getUsers();
					$data["success"] = true;
					$data["status"] = 200;
				}
			break; 


			case 'GET_furnitures' : 
				if ($idEntite1){
					// GET /AR-design/api/furnitures/<id>
					$data["furniture"] = getFurniture($idEntite1,$connectedId);
					$data["success"] = true;
					$data["status"] = 200;
				} else {
					// GET /AR-design/api/furnitures
					$data["furnitures"] = getFurnitures();
					$data["success"] = true;
					$data["status"] = 200; 
				}
			break;

			case 'GET_users_furnitures' : 
				if ($idEntite1)
				if ($idEntite2) {
					// GET /AR-design/api/users/<id>/furnitures/<id>
					$data["furniture"] = getFurniture($idEntite2, $idEntite1);
					$data["success"] = true;
					$data["status"] = 200;
				} else {
					// GET /AR-design/api/users/<id>/furnitures
					$data["furnitures"] = getFurnituresUser($idEntite1);
					$data["success"] = true;
					$data["status"] = 200;
				}
			break;

			case 'GET_walls' : 
				if ($idEntite1){
					// GET /AR-design/api/walls/<id>
					$data["wall"] = getWall($idEntite1,$connectedId);
					$data["success"] = true;
					$data["status"] = 200;
				} else {
					// GET /AR-design/api/walls
					$data["walls"] = getWalls();
					$data["success"] = true;
					$data["status"] = 200; 
				}
			break;

			case 'GET_users_walls' : 
				if ($idEntite1)
				if ($idEntite2) {
					// GET /AR-design/api/users/<id>/walls/<id>
					$data["wall"] = getWall($idEntite2, $idEntite1);
					$data["success"] = true;
					$data["status"] = 200;
				} else {
					// GET /AR-design/api/users/<id>/walls
					$data["walls"] = getWallsUser($idEntite1);
					$data["success"] = true;
					$data["status"] = 200;
				}
			break;

			case 'GET_standardFurnitures' : 
				if ($idEntite1){
					// GET /AR-design/api/standardFurnitures/<id>
					$data["standardFurnitures"] = getStandardFurniture($idEntite1);
					$data["success"] = true;
					$data["status"] = 200;
				} else {
					// GET /AR-design/api/standardFurnitures
					$data["standardFurnitures"] = getStandardFurnitures();
					$data["success"] = true;
					$data["status"] = 200; 
				}
			break;
			
			// POST 

			case 'POST_users' : 
				// POST /AR-design/api/users?pseudo=&pass=&mail...
				if ($pseudo = valider("pseudo"))
				if ($pass = valider("password")) 
				if ($mail = valider("mail")){
					$id = mkUser($pseudo, $mail,$pass); 
					$data["user"] = getUser($id);
					$data["success"] = true;
					$data["status"] = 201;
				}
			break; 

			case 'POST_users_furnitures' :
				// POST /AR-design/api/users/<id>/furnitures?width=&height=&legnth=...
				if ($idEntite1)
				if ($width = valider("width"))
				if ($height = valider("height"))
				if ($length = valider("length")){
					$id = mkFurniture($idEntite1, $width, $height, $length); 
					$data["furniture"] = getFurniture($id);
					$data["success"] = true;
					$data["status"] = 201;
				}
			break; 

			case 'POST_users_walls' :
				// POST /AR-design/api/users/<id>/walls?width=&height=&
				if ($idEntite1)
				if ($width = valider("width"))
				if ($height = valider("height")){
					$id = mkWall($idEntite1, $width, $height); 
					$data["wall"] = getWall($id);
					$data["success"] = true;
					$data["status"] = 201;
				}
			break; 

		

			case 'POST_standardFurnitures' :
				// POST /AR-design/api/standardFurnitures?width=&height=&length=&url=
				//if ($idEntite1)
				if ($width = valider("width"))
				if ($height = valider("height"))
				if ($length = valider("length"))
				if ($url = valider("url")){
					$id = mkStandardFurniture($width, $height, $length, $url);			
					$data["standardFurniture"] = getStandardFurniture($id);
					$data["success"] = true; 
					$data["status"] = 201;
					//if ($url = valider("url"))
				}
			break; 


			case 'PUT_authenticate' : 
				// régénère un hash ? 
				$data["hash"] = mkHash($connectedId); 
				$data["success"] = true; 
				$data["status"] = 200;
			break; 

			case 'PUT_users' :
				// todo verify the connected id is the user of the entite
				// PUT  /AR-design/api/users/<id>?pass=...
				if ($idEntite1)
				if ($pass = valider("pass")) {
					if (chgPassword($idEntite1,$pass)) {
						$data["user"] = getUser($idEntite1);
						$data["success"] = true; 
						$data["status"] = 200;
					} else {
						// erreur 
					}
				}
			break; 

			case 'PUT_users_furnitures2' :
				// todo verify the connected id is the user of the entite
				// PUT  /AR-design/api/users/<id>/furnitures/1?width=...
				if ($idEntite1)
				if ($idEntite2)
				if ($width = valider("width")) {
					if (chgWidthFurniture($idEntite2, $idEntite1,$width)) {
						$data["furniture"] = getFurniture($idEntite2, $idEntite1);
						$data["success"] = true; 
						$data["status"] = 200;
					} else {
						// erreur 
					}
				}
			break; 

					
			case 'PUT_users_furnitures' : 
				// PUT /AR-design/api/users/<id>/furnitures/<id>?width=&height=...
				if ($idEntite1) 
				if ($idEntite2) {
					$width = valider("width");
					$height = valider("height");
					$length = valider("length");   
					if (chgFurniture($idEntite2,$idEntite1, $width, $height, $length)) {
						$data["furniture"] = getFurniture($idEntite2, $idEntite1);
						$data["success"] = true; 
						$data["status"] = 200;
					} else {
						// erreur
					}
				}
			break; 
				
			
			case 'PUT_users_walls' : 
				// PUT /AR-design/api/users/<id>/walls/<id>?width=&height=...
				if ($idEntite1)
				if ($idEntite2){
					$width = valider("width");
				    $height = valider("height");
					if (chgWall($idEntite2, $idEntite1, $width, $height)) {
						$data["wall"] = getWall($idEntite2);
						$data["success"] = true; 
						$data["status"] = 200;
					} else {
						// erreur
					}
				}
			break; 
				
				
			case 'PUT_standardFurniture' : 
				// PUT /AR-design/api/standardFurnitures/<id>?url=
				if ($idEntite1)
				if ($url= valider("url")){
					if (chgStandardFurnitureUrl($id, $url)) {
						$data["standardFrniture"] = getStandardFurniture($idEntite1);
						$data["success"] = true; 
						$data["status"] = 200;
					} else {
						// erreur
					}
				}
			break; 

			case 'DELETE_users' : 
				// DELETE /AR-design/api/users/<id> 
				if ($idEntite1) {
					if (rmUser($idEntite1)) {
						$data["success"] = true;
						$data["status"] = 200;
					} else {
						// erreur 
					} 
				}
			break; 

			case 'DELETE_users_furnitures' : 
				// DELETE /AR-design/api/users/<id>/furnitures/<id>
				if ($idEntite1)
				if ($idEntite2) {
					if (rmFurniture($idEntite2, $idEntite1)) {				
						$data["success"] = true;
						$data["status"] = 200; 
					} else {
						// erreur 
					}
				}
			break; 

			case 'DELETE_users_walls' : 
				// DELETE /AR-design/api/users/<id>/furnitures/<id>
				if ($idEntite1)
				if ($idEntite2) {
					if (rmWall($idEntite2, $idEntite1)) {				
						$data["success"] = true;
						$data["status"] = 200; 
					} else {
						// erreur 
					}
				}
			break; 

			case 'DELETE_standardFurnitures' : 
				// DELETE /api/standardFurnitures/<id>
				if ($idEntite1) {
					if (rmStandardFurniture($idEntite1)) {				
						$data["success"] = true;
						$data["status"] = 200; 
					} else {
						// erreur 
					}
				}
			break; 
		} // switch(action)
	} //connected
}

switch($data["status"]) {
	case 200: header("HTTP/1.0 200 OK");	break;
	case 201: header("HTTP/1.0 201 Created");	break; 
	case 202: header("HTTP/1.0 202 Accepted");	break;
	case 204: header("HTTP/1.0 204 No Content");	break;
	case 400: header("HTTP/1.0 400 Bad Request");	break; 
	case 401: header("HTTP/1.0 401 Unauthorized");	break; 
	case 403: header("HTTP/1.0 403 Forbidden");	break; 
	case 404: header("HTTP/1.0 404 Not Found");		break;
	default: header("HTTP/1.0 200 OK");
		
}

echo json_encode($data);

?>
