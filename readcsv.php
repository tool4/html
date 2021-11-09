<h1></h1>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
          var data = google.visualization.arrayToDataTable([
<?php
          $csv_file = $_GET["csv_file"];
          $file_index = $_GET["file_index"];
          $col_with_data = $_GET["col"];
          $from_y = $_GET["from_y"];
          if ($from_y == "")
              $from_y == "0";
          $row = 1;
          if (($handle = fopen($csv_file, "r")) !== FALSE)
          {
              $row = 0;
              while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
              {
                  $num = count($data);
                  if ($row % 10 == 0 || $row == 1)
                  {
                      echo "\n\t\t\t\t";
                  }
                  //if ($row > 0)
                      echo " [";
                  for ($c=0; $c < $num; $c++)
                  {
                      $entry = trim($data[$c]);
                      $entry1 = str_replace(":", "", $entry);
                      if ($data[$c] && ($c == $col_with_data || $c == 0))
                      {
                          if ($row == 0)
                              $title =  $entry;
                          if ($row > 0 and $c > 0)
                              echo $entry;
                          else
                              echo "'" . $entry1 . "'";
                          if ($c < $num - 2)
                              echo ", ";
                      }
                  }
                  //                  if ($row > 0)
                      echo "],";
                  $row=$row +1;
              }
              fclose($handle);
          }
          else
          {
              echo "Cannot open file: " . $csv_file;
          }
          ?>
          ]);

          var options = {
<?php
              $name = $_GET["name"];
              $name = $csv_file;
              $name = str_replace(".csv", "", $name);
              $name = str_replace("netinfo", "", $name);
              $name = str_replace("temp", "", $name);
              $name = $title . " " . $name;
              $name = str_replace("_", " ", $name);
              if($name != "")
                  echo "\t\t\t\ttitle: '" . $name . "'";
              else
                  echo "\t\t\t\ttitle: 'Chart'";
?>,
                curveType: 'function',
                legend: { position: 'bottom' },
<?php if ($from_y != "auto" && $from_y != "")
      {
          echo "                vAxis: {\n";
          echo "                    viewWindow: {\n";
          echo "                        min: " . $from_y . ",\n";
          echo "                        interval: 1,\n";
          echo "                    },\n";
          echo "                },\n";
       }
?>
              explorer: { axis: 'horizontal', keepInBounds: true, maxZoomIn: 4.0 },
              colors: ['#D44E41'],
          };
          //var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
          chart.draw(data, options);
      };

     function drawChart1() {
        var data1 = google.visualization.arrayToDataTable([
          ['Year', 'Sales'],
          [new Date(2001, 01, 01), 30],
          [new Date(2002, 01, 01), 70],
          [new Date(2003, 01, 01), 45],
          [new Date(2004, 01, 01), 99],
          [new Date(2005, 01, 01), 22],
          [new Date(2006, 01, 01), 0],
          [new Date(2007, 01, 01), 89],
          [new Date(2008, 01, 01), 30],
          [new Date(2009, 01, 01), 32],
        ]);

        var options1 = {
          title: 'Company Performance',
          hAxis: {
            title: 'Year',
            titleTextStyle: {
              color: '#333'
            },
            slantedText: true,
            slantedTextAngle: 80
          },
          vAxis: {
            minValue: 0
          },
          explorer: {
            axis: 'horizontal',
            keepInBounds: true,
            maxZoomIn: 4.0
          },
          colors: ['#D44E41'],
        };
        var chart1 = new google.visualization.LineChart(document.getElementById('curve_chart'));
        //var chart1 = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart1.draw(data1, options1);
     }
    </script>
<?php
$files = scandir(".");
$total = count($files);
for($x = 0; $x <= $total; $x++)
{
    $ext = substr($files[$x], -3, 3);
    if ($ext == "csv")
    {
        $csv_files_array[] = $files[$x];
        ++$csv_index;
    }
}

$next_file = intval($file_index);
$col = $col_with_data;
$next_col = $col;

function get_url($file, $col, $title, $from_y)
{
    global $csv_files_array;
    $url = "readcsv.php?csv_file=".
            $csv_files_array[$file].
            "&col=".$col.
            "&name=".$title.
            "&file_index=".$file.
            "&from_y=".$from_y;
    echo $url;
    return $url;
}
?>
    <script type="text/javascript">
        function KeyPress (event){
            var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
            var url = window.location.href;
            var pos = url.lastIndexOf("/") + 1;
            url = url.substring(0, pos);
            //alert(url);
            var reload = false;
            var nextfile = <?php echo intval($file_index); ?>;
            var nextcol = <?php echo intval($col); ?>;

            //alert ("The Unicode character code is: " + chCode);
            if(chCode == 100)
            {
                <?php
                $next_file = intval($file_index) + 1;
                if ($next_file >= $csv_index)
                    $next_file = 0;
                $url = "readcsv.php?csv_file=".
                     $csv_files_array[$next_file].
                     "&col=".$next_col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y=".$from_y;
                echo 'url = url + "' . $url . '";';
                ?>
                reload = true;
            }
            if(chCode == 97)
            {
                <?php
                $next_file = intval($file_index) - 1;
                if ($next_file < 0)
                    $next_file = $csv_index -1;
                $url = "readcsv.php?csv_file=" .
                     $csv_files_array[$next_file] .
                     "&col=".$next_col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y=".$from_y;
                echo 'url = url + "' . $url . '";';
                ?>
                reload = true;
            }
            if(chCode == 119)
            {
                <?php
                $next_file = intval($file_index);
                $next_col = $col + 1;
                if ($next_col > 3)
                    $next_col = 1;
                $url = "readcsv.php?csv_file=".
                     $csv_files_array[$next_file].
                     "&col=".$next_col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y=".$from_y;
                echo 'url = url + "' . $url . '";';
                ?>
                reload = true;
            }
            if(chCode == 115)
            {
                <?php
                $next_file = intval($file_index);
                $next_col = $col - 1;
                if ($next_col < 1)
                    $next_col = 3;
                $url = "readcsv.php?csv_file=".
                     $csv_files_array[$next_file].
                     "&col=".$next_col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y=".$from_y;
                echo 'url = url + "' . $url . '";';
                ?>
                reload = true;
            }

            if (reload)
            {
                open_url(url);
            }
        }
        function open_url(url)
        {
            window.open(url,"_self");
        }
        function reload()
        {
            window.open(window.location.href,"_self");
            if(document.getElementById("test1").checked )
                from_y = "0";
            else
                from_y = "auto";
            <?php
                global $csv_files_array;
                global $file_index;
                global $col;
                global $title;
                echo "";
                $url = "readcsv.php?csv_file=".$csv_files_array[$next_file].
                     "&col=".$col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y="; ?>
            url = "<?php echo $url?>";
            url = url + from_y;
            open_url(url);
        }
        function prev_file()
        {
            window.open(window.location.href,"_self");
            if(document.getElementById("test1").checked )
                from_y = "0";
            else
                from_y = "auto";
            <?php
                global $csv_files_array;
                global $file_index;
                global $col;
                global $title;
                $next_file = intval($file_index) - 1;
                $url = "readcsv.php?csv_file=".$csv_files_array[$next_file].
                     "&col=".$col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y="; ?>
            url = "<?php echo $url?>";
            url = url + from_y;
            open_url(url);
        }
        function next_file()
        {
            window.open(window.location.href,"_self");
            if(document.getElementById("test1").checked )
                from_y = "0";
            else
                from_y = "auto";
            <?php
                global $csv_files_array;
                global $file_index;
                global $col;
                global $title;
                $next_file = intval($file_index) + 1;
                $url = "readcsv.php?csv_file=".$csv_files_array[$next_file].
                     "&col=".$col.
                     "&name=".$title.
                     "&file_index=".$next_file.
                     "&from_y="; ?>
            url = "<?php echo $url?>";
            url = url + from_y;
            open_url(url);
        }
        //action=<?php get_url($file_index, $col, $title, $from_y)?>
        function Load()
        {
            document.getElementById("test1").checked = <?php
                global $from_y;
                if ($from_y != "auto")
                {
                    echo "true;\n";
                }
                else
                {
                    echo "false;\n";
                }
            ?>
        }
    </script>
</head>
  <body onload="Load()" onkeypress="KeyPress(event)">
    <form method="post" >
        <input onclick="prev_file()" type="button" id="button" value="Prev">
        <input onclick="reload()" type="checkbox" name="test1" id="test1" value="value1"> Scale from 0
        <input onclick="reload()" type="checkbox" name="test2" id="test2" value="value1"> Option 2
        <input onclick="next_file()" type="button" id="button" value="Next">
    </form>
    <div id="curve_chart" style="width: 100%; height: 100%"></div>
  </body>
</html>


<?php
if(0)
{
    $csv_file = $_GET["csv_file"];
    echo $csv_file;
    $row = 1;
    if (($handle = fopen($csv_file, "r")) !== FALSE)
    {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            $num = count($data);
            echo "<br/>\n\t[";
            $row++;
            for ($c=0; $c < $num; $c++) {
                if ($data[$c])
                    echo "'" . $data[$c] . "'";
                if ($c < $num - 2)
                    echo ", ";
            }
            echo "],";
        }
        fclose($handle);
    }
}
?>
