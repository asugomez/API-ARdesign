<?php
include_once("maLibSQL.pdo.php"); 
// définit les fonctions SQLSelect, SQLUpdate...


// Users ///////////////////////////////////////////////////

function validerUser($pseudo, $pass){
	$SQL = "SELECT id FROM users WHERE pseudo='$pseudo' AND pass='$pass'";
	if ($id=SQLGetChamp($SQL))
		return getHash($id);
	else return false;
}

function hash2id($hash) {
	$SQL = "SELECT id FROM users WHERE hash='$hash'";
	return SQLGetChamp($SQL); 
}

function hash2pseudo($hash) {
	$SQL = "SELECT pseudo FROM users WHERE hash='$hash'";
	return SQLGetChamp($SQL); 
}

function getUsers(){
	$SQL = "SELECT id,pseudo FROM users";
	return parcoursRs(SQLSelect($SQL));
}

function getUser($idUser){
	$SQL = "SELECT id,pseudo FROM users WHERE id='$idUser'";
	$rs = parcoursRs(SQLSelect($SQL));
	if (count($rs)) return $rs[0]; 
	else return array();
}

function getHash($idUser){
	$SQL = "SELECT hash FROM users WHERE id='$idUser'";
	return SQLGetChamp($SQL);
}

function mkHash($idUser) {
	// génère un (nouveau) hash pour cet user
	// il faudrait ajouter une date d'expiration
	$dataUser = getUser($idUser);
	if (count($dataUser) == 0) return false;
 
	$payload = $dataUser["pseudo"] . date("H:i:s");
	$hash = md5($payload); 
	$SQL = "UPDATE users SET hash='$hash' WHERE id='$idUser'"; 
	SQLUpdate($SQL); 
	return $hash; 
}

function mkUser($pseudo, $mail, $pass){
	$SQL = "INSERT INTO users(pseudo,mail,pass) VALUES('$pseudo', '$mail', '$pass')";
	$idUser = SQLInsert($SQL);
	mkHash($idUser); 
	return $idUser; 
}


function rmUser($idUser) {
	$SQL = "DELETE FROM users WHERE id='$idUser'";
	return SQLDelete($SQL);
}

function chgPassword($idUser,$pass) {
	$SQL = "UPDATE users SET pass='$pass' WHERE id='$idUser'";
	return SQLUpdate($SQL);
}

// Furniture ///////////////////////////////////////////////////


function getFurnitures(){
	$SQL = "SELECT f.id, u.pseudo, f.width, f.height, f.length FROM furnitures f INNER JOIN users u ON f.idUser = u.id"; 
	return parcoursRs(SQLSelect($SQL));
}

function getFurniture($id,$idUser=false){
	$SQL = "SELECT id, width, height, length FROM furnitures WHERE id='$id'"; 
	if ($idUser)
		$SQL .= " AND idUser='$idUser'"; 
	$rs = parcoursRs(SQLSelect($SQL));
	if (count($rs)) return $rs[0]; 
	else return array();
}

function getFurnituresUser($idUser){
	$SQL = "SELECT id, width, height, length FROM furnitures WHERE idUser='$idUser'"; 
	return parcoursRs(SQLSelect($SQL));
}

function mkFurniture($idUser, $width, $height, $length){
	$SQL = "INSERT INTO furnitures(idUser, width, height, length) VALUES('$idUser', '$width', '$height', '$length')";
	return SQLInsert($SQL);
}

function chgFurniture($id, $idUser, $width=false, $height=false, $length=false){
	$SQL =  "UPDATE walls SET `width`='$width'";//, `height`='$height'";//, `len`='$length'";
	/*if($width and $height and $length)
		$SQL .= " SET width='$width', height='$height', length='$length'";
	else if($width and $height)
		$SQL .= " SET width='$width', height='$height'" ;
	else if($width and$length)
		$SQL .= " SET width='$width', length='$length'" ;
	else if($height and$length)
		$SQL .= " SET height='$height', length='$length'" ;
	else if($width)
		$SQL .= " SET width='$width'" ;
	else if($height)
		$SQL .= " SET height='$height'" ;
	else if($length)
		$SQL .= " SET length='$length'" ;*/
	$SQL .= " WHERE id='$id' AND idUser='$idUser'";
	return SQLUpdate($SQL);
}

function chgWidthFurniture($id, $idUser, $width){
	$SQL =  "UPDATE walls SET `width`='$width'";
	$SQL .= " WHERE id='$id' AND idUser='$idUser'";
	return SQLUpdate($SQL);
}

function rmFurniture($id, $idUser=false) {
	$SQL = "DELETE FROM furnitures WHERE id='$id'";
	if ($idUser) $SQL .= " AND idUser='$idUser'"; 
	return SQLDelete($SQL);
}


// Walls///////////////////////////////////////////////////



function getWalls(){
	$SQL = "SELECT w.id, u.pseudo, w.width, w.height FROM walls w INNER JOIN users u ON w.idUser = u.id"; 
	return parcoursRs(SQLSelect($SQL));
}

function getWall($id,$idUser=false){
	$SQL = "SELECT id, width, height FROM walls WHERE id='$id'"; 
	if ($idUser)
		$SQL .= " AND idUser='$idUser'"; 
	$rs = parcoursRs(SQLSelect($SQL));
	if (count($rs)) return $rs[0]; 
	else return array();
}

function getWallsUser($idUser){
	$SQL = "SELECT id, width, height FROM walls WHERE idUser='$idUser'"; 
	return parcoursRs(SQLSelect($SQL));
}

function mkWall($idUser, $width, $height){
	$SQL = "INSERT INTO walls(idUser, width, height) VALUES('$idUser', '$width', '$height')";
	return SQLInsert($SQL);
}

function chgWall($id, $idUser, $width=false, $height=false){
	$SQL =  "UPDATE walls";
	if($width and $height)
		$SQL .= " SET width='$width', height='$height'";
	else if($width)
		$SQL .= " SET width='$width'" ;
	else if($height)
		$SQL .= " SET height='$height'" ;
	$SQL .= " WHERE id='$id' AND idUser='$idUser'";
	return SQLUpdate($SQL);
}

function rmWall($id, $idUser=false) {
	$SQL = "DELETE FROM walls WHERE id='$id'";
	if ($idUser) $SQL .= " AND idUser='$idUser'"; 
	return SQLDelete($SQL);
}


// Standard Furnitures /////////////////////////

function getStandardFurnitures(){
	$SQL = "SELECT * FROM standardFurnitures"; 
	return parcoursRs(SQLSelect($SQL));
}

function getStandardFurniture($id){
	$SQL = "SELECT * FROM standardFurnitures WHERE id='$id'"; 
	$rs = parcoursRs(SQLSelect($SQL));
	if (count($rs)) return $rs[0]; 
	else return array();
}


function mkStandardFurniture($width, $height, $length, $url){
	$SQL = "INSERT INTO standardFurnitures(width, height, length, url) VALUES('$width', '$height', '$length', '$url')";
	return SQLInsert($SQL);
}

function rmStandardFurniture($id) {
	$SQL = "DELETE FROM standardFurnitures WHERE id='$id'";
	return SQLDelete($SQL);
}

function chgStandardFurnitureUrl($id, $url) {
	$SQL = "UPDATE standardFurnitures SET url='$url' WHERE id='$id'";
	return SQLUpdate($SQL);
}



?>
