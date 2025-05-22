<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start(); // si necesitas variables de sesión

$start = $_POST['start'] ?? $_GET['start'];
$end = $_POST['end'] ?? $_GET['end'];
$usuario = $_POST['usuario'] ?? null;

include("../conexion/bdd.php");

// Consulta eventos en el rango

$sql = "SELECT p.id, p.codigo, p.id_colegio, p.color, p.start, p.end, p.id_objetivo, u.id as uid, CONCAT(u.nombres,' ',u.apellidos) as fullname FROM plan_trabajo p JOIN usuarios u ON u.id=p.id_promotor WHERE (start BETWEEN :start AND :end OR end BETWEEN :start AND :end) AND u.tipo=4;";


$stmt = $bdd->prepare($sql);
$stmt->execute([
  ':start' => $start,
  ':end' => $end
]);

$events = [];

while ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
  // Procesar título, participantes, colegio, objetivo igual que hacías
  // Colegio
  $col = $bdd->query("SELECT colegio FROM colegios WHERE id='".$event['id_colegio']."' ")->fetch();
  $objetivo = $bdd->query("SELECT objetivo FROM objetivos WHERE id='".$event['id_objetivo']."' ")->fetch();
  
  $sql_parti = "SELECT CONCAT (nombres, ' ', apellidos) as parti, t.tipo 
                FROM usuarios u 
                JOIN plan_trabajo p ON u.id=p.id_promotor 
                JOIN tipos_notifi t ON t.id=p.agendamiento 
                WHERE p.codigo='".$event['codigo']."' 
                  AND u.id !='".$_SESSION['id']."' 
                GROUP BY p.codigo";

  $req_parti = $bdd->prepare($sql_parti);
  $req_parti->execute();
  $participantes = $req_parti->fetchAll();

  $partics = '';
  foreach($participantes as $participante) {
    $tipo_noti = explode(" ", $participante["tipo"]);
    $partics .= $participante["parti"] . " (" . ucfirst($tipo_noti[1]) . "), ";
  }

  // Ajustar fechas
  $start = explode(" ", $event['start']);
  $end = explode(" ", $event['end']);
  $start = ($start[1] == '00:00:00') ? $start[0] : $event['start'];
  $end = ($end[1] == '00:00:00') ? $end[0] : $event['end'];

  $events[] = [
    'id' => $event['id'],
    'title' => $col['colegio']."\n".$objetivo['objetivo']."\nCon: ".$partics,
    'start' => $start,
    'end' => $end,
    'color' => $event['color'],
    'url' => 'visita_detallado_semanal.php?evento=' . $event['id']
  ];
}

echo json_encode($events);
