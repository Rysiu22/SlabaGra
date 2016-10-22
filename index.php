<?php

//Mapa
$json_plik = 'mapa.txt';

if( isset($_POST['json']) )
{
	$json = json_decode(str_replace('\"','"',$_POST['json']) , true);

	if( ! file_exists($json_plik))
	{
		$tmp = json_encode($json);
		$file = fopen($json_plik, 'w'); fwrite($file, $tmp);
		fclose($file);
	}
	else
	{
		$tmp = fread(fopen($json_plik, "r"), filesize($json_plik));
		$dane = json_decode($tmp);
		
		//dodac sprawdzanie wyjscia poza mape. Odczyt innej mapy?
		//dodac kolizje z drzewami
		if( isset($json['run']) )
		{
			$dane->ja->x += $json['run']['x'];
			$dane->ja->y += $json['run']['y'];
		}

		$tmp = json_encode($dane);

		$file = fopen($json_plik, 'w'); fwrite($file, $tmp);
		fclose($file);

	}

	header("Access-Control-Allow-Origin:*");
	header("Content-type: application/json");
	echo $tmp;
	//echo json_encode(array( 'json' => $json, 'dane' => $dane) );
	exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<head>
<title>Pse³do gra</title>

<script src="http://localhost/gra2/jquery-3.1.1.js"></script>

<style>

#map {
	display: flex;
	justify-content: center;
	}

	#debug {
		width:40%;
  background-color:lightblue;
  border:3px dashed red;
  font-size:1.4em;
	}

table {border-collapse: collapse;}
td    {padding: 0px;}


    </style>
	
	
</head>
<body>
Nick: Ja<br />
Œwiat: 1<br />
Graczy: 0<br />
Lvl: 1<br />
<div id="debug">Zmienna treœæ</div><br />

<form action="javascript:run(lewo);">
  <input type="submit" value="<-">
</form>

<form action="javascript:run(prawo);">
  <input type="submit" value="->">
</form>

<form action="javascript:run(gora);">
  <input type="submit" value="^">
</form>

<form action="javascript:run(dol);">
  <input type="submit" value="v">
</form>


<form action="javascript:run(mapa);">
  <input type="submit" value="Run">
</form>

<div id="result"></div>

<div id="map"></div>

<script>


</script>
<script>

var prawo = {"run":{"x":1,"y":0}};
var lewo = {"run":{"x":-1,"y":0}};
var gora = {"run":{"x":0,"y":-1}};
var dol = {"run":{"x":0,"y":1}};

var mapa = {"ja":{"x":2,"y":2},"drzewo":{"x":1,"y":1},"drzewo2":{"x":3,"y":3}};

//jquery
strona = 'http://localhost/gra2/index.php';

function run(dane)
{
 $.post(
 strona, 
 {"json": JSON.stringify(dane)},
 function( data ) { 
 //alert(JSON.stringify(data)); 
 var licznik = "Returned from server:<br />\n"+JSON.stringify(data)+"<br />\n";
 $('#debug').html(licznik);
 mapa = data;
 $('#map').html(rysuj_mape());
 })

 
 .fail(function(dane) { $('#info').html(JSON.stringify(dane)); }) 
 //.fail(function(dane) { alert( "error - nie pobrano -> "+ JSON.stringify(dane) ); }) 
 //.done(function(dane) { alert( "return server - " + JSON.stringify(dane) ); })

 };  // end run()

 
//mapa




function rysuj_mape() //rozmiar_x, rozmiar_y, dostepne_kafelki)
{
	rozmiar_x=10, rozmiar_y=10, dostepne_kafelki=0;
	var a=0;
	var out="";
	//function obrazek(){return (a++%dostepne_kafelki)+1;}
	function obrazek(){return 0;}
	function write(dane){ out += dane; }
	
	var obiekty = Object.keys(mapa);
	var obiekt = Object.keys(mapa)[0];
	
	write('\n<table style="border: 0px solid black; CELLSPACING:0px; aling:center;">\n');
	for(var y=0; y<rozmiar_y; y++)
	{
		write('\t<tr>\n');
		for(var x=0; x<rozmiar_x; x++)
		{
			write('\t\t<td>\n');
			write('<img style="" src="images/'+obrazek()+'.gif" /><br />');
			for(var obiekt in mapa)
			{
				if( mapa.hasOwnProperty(obiekt) && y==mapa[obiekt].y && x==mapa[obiekt].x)
					write('<img style="position: relative; left: 0px; bottom: 0px; margin-top: -100%;" src="images/'+obiekt+'.gif" />');
			}
			write('\t\t</td>\n');
					
		}
		write('\t</tr>\n');
	}
	write('</table>\n ');
	
	return out;
}

//$('#map').html(rysuj_mape());
run({"run":{"x":0,"y":0}});
</script>



</body>
</html>