<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Inkpulse - Calendario Consultorías</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/fullcalendar/fullcalendar.css"
		/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
	</head>
	<body>
		<?php
		require_once("php/aut.php");
		include("template/nav_side.php"); ?>
		
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Calendario consultorías</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Inicio</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Calendario consultorías
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="calendar-wrap">
							<div id="calendar"></div>
						</div>
						
					</div>
				</div>
				<?php include("template/footer.php"); ?>
			</div>
		</div>
		
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/fullcalendar/fullcalendar.min.js"></script>
		<!--<script src="vendors/scripts/calendar-setting.js"></script>-->
		<script>
			
			$(document).ready(function() {

				var date = new Date();
		       	var yyyy = date.getFullYear().toString();
		       	var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
		       	var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();
		
				$('#calendar').fullCalendar({
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		        	monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		        	dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		    		dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],

		    		defaultView: 'agendaWeek',
		    		
		    		allDaySlot: false,

		    		nowIndicator: true,
		    		slotDuration: '01:00:00',
					header: {
						 language: 'es',
						left: 'prev,next',
						center: 'title',
						right: 'month,agendaWeek,agendaDay',

					},
					hiddenDays: [ 0 ],
					defaultDate: yyyy+"-"+mm+"-"+dd,
					editable: true,
					eventLimit: true, // allow "more" link when too many events
					selectable: true,
					selectHelper: true,
					minTime: "06:00:00",
					maxTime: "20:00:00",
					
					eventDrop: function(event, delta, revertFunc) { // si changement de position

						edit(event);

					},
					eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

						edit(event);

					},
					events: {
					  url: 'ajax/eventos_consultorias.php', // nuevo script que devuelve eventos
					  method: 'POST',
					  failure: function() {
					    alert('Error al cargar eventos');
					  }
					}
				});
		
				

				$(".fc-month-button").text("Mes");
				$(".fc-agendaWeek-button").text("Semana");
				$(".fc-agendaDay-button").text("Día");
		
			});


		</script>
	
	</body>
</html>
