<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=new_crm;charset=utf8', 'new_crm', '7q48jd4_Z');
	date_default_timezone_set('America/Bogota');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}