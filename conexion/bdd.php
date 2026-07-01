<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=crm_panama;charset=utf8', 'root', '');
	date_default_timezone_set('America/Bogota');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}