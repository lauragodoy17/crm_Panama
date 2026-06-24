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

$sql = "SELECT id, codigo, id_colegio, otro_lugar, color, start, end, id_objetivo, otro_objetivo FROM plan_trabajo WHERE (start BETWEEN :start AND :end OR end BETWEEN :start AND :end) AND id_promotor='".$_SESSION['id']."'";

$stmt = $bdd->prepare($sql);
$stmt->execute([
  ':start' => $start,
  ':end' => $end
]);

$events = [];

while ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
  // Colegio
  if ($event['id_colegio'] == 0) {
    $col = ['colegio' => $event['otro_lugar'] ?: 'Otro lugar'];
  } else {
    $col = $bdd->query("SELECT colegio FROM colegios WHERE id='".$event['id_colegio']."' ")->fetch();
  }
  if ($event['id_objetivo'] == 0) {
    $objetivo = ['objetivo' => $event['otro_objetivo'] ?: 'Otro'];
  } else {
    $objetivo = $bdd->query("SELECT objetivo FROM objetivos WHERE id='".$event['id_objetivo']."' ")->fetch();
  }
  
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

  if (empty($col['colegio'])) {
    $col['colegio']="";
  }
  $events[] = [
    'id' => $event['id'],
    'title' => $col['colegio']."\n".$objetivo['objetivo']."\nCon: ".$partics,
    'start' => $start,
    'end' => $end,
    'color' => $event['color'],
    'url' => 'evento.php?evento=' . $event['id']
  ];
}

echo json_encode($events);
