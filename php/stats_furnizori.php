<?php
include('conexiune.php');
?>
<h2>Statistici furnizori</h2>
<canvas style="display: inline;" id="canvas" height="300" width="300"></canvas>
<script>
	var doughnutData = [
		{
			value: 30,
			color: "#F7464A"
		},
		{
			value: 50,
			color: "#46BFBD"
		},
		{
			value: 100,
			color: "#FDB45C"
		},
		{
			value: 40,
			color: "#949FB1"
		},
		{
			value: 120,
			color: "#4D5360"
		}
	];

	var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);
</script>
<h2>Alte statistici</h2>
<canvas style="display: inline;" id="canvas1" height="300" width="300"></canvas>
<script>

	var pieData = [
		{
			value: 30,
			color: "#F38630"
		},
		{
			value: 50,
			color: "#E0E4CC"
		},
		{
			value: 100,
			color: "#69D2E7"
		}

	];

	var myPie = new Chart(document.getElementById("canvas1").getContext("2d")).Pie(pieData);

</script>