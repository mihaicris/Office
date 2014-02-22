<?php
include('conexiune.php');
$width = $_POST["width"] * 0.99;
$width = $width > 900 ? 900 :  $width;

?>

<h2>Statistici ofertare</h2>
<canvas id="canvas2" height="300" width="<?php echo $width; ?>"></canvas>
<script>
    var barChartData = {
        labels:   ["Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"],
        datasets: [
            {
                fillColor:   "rgba(64, 150, 241, 0.3)",
                strokeColor: "rgba(200, 200, 200, 0.6)",
                data:        [6.5, 5.9, 9.0, 8.1, 5.6, 5.6, 8.0, 13.4, 8.9, 12.0, 9.0, 6.0]
            }
        ]
    };
    var options = {
        //Boolean - If we show the scale above the chart data
        scaleOverlay:        false,

        //Boolean - If we want to override with a hard coded scale
        scaleOverride:       false,

        //** Required if scaleOverride is true **
        //Number - The number of steps in a hard coded scale
        scaleSteps:          null,
        //Number - The value jump in the hard coded scale
        scaleStepWidth:      null,
        //Number - The scale starting value
        scaleStartValue:     null,

        //String - Colour of the scale line
        scaleLineColor:      "rgba(255,255,255,1)",

        //Number - Pixel width of the scale line
        scaleLineWidth:      1,

        //Boolean - Whether to show labels on the scale
        scaleShowLabels:     true,

        //Interpolated JS string - can access value

        //String - Scale label font declaration for the scale label
        scaleFontFamily:     "Roboto Condensed",

        //Number - Scale label font size in pixels
        scaleFontSize:       12,

        //String - Scale label font weight style
        scaleFontStyle:      "normal",

        //String - Scale label font colour
        scaleFontColor:      "#DDD",

        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines:  true,

        //String - Colour of the grid lines
        scaleGridLineColor:  "rgba(255,255,255,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth:  1,

        //Boolean - If there is a stroke on each bar
        barShowStroke:       true,

        //Number - Pixel width of the bar stroke
        barStrokeWidth:      1,

        //Number - Spacing between each of the X value sets
        barValueSpacing:     <?php echo $width/20; ?>,

        //Number - Spacing between data sets within X values
        barDatasetSpacing:   1,

        //Boolean - Whether to animate the chart
        animation:           false,

        //Number - Number of animation steps
        animationSteps:      60,

        //String - Animation easing effect
        animationEasing:     "easeOutQuart",

        //Function - Fires when the animation is complete
        onAnimationComplete: null
    };
    var myLine = new Chart(document.getElementById("canvas2").getContext("2d")).Bar(barChartData, options);
</script>