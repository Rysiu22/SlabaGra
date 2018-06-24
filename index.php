<?php
error_reporting(0);

//Mapa
$json_plik = 'mapa.txt';

if( isset($_COOKIE['PHPSESSID']) )
{
	$json_plik = $_COOKIE['PHPSESSID'].".json";
}
else
{
	$phpsessid = md5(rand()+rand()+rand());
	$json_plik = $phpsessid.".json";
	setcookie('PHPSESSID', $phpsessid);
}
	
if( isset($_POST['json']) )
{
	$json = json_decode(str_replace('\"','"',$_POST['json']) , true);

	if( ! file_exists($json_plik))
	{
		copy('mapa.txt',$json_plik);
		$tmp = fread(fopen($json_plik, "r"), filesize($json_plik));
	}
	else
	{
		$tmp = fread(fopen($json_plik, "r"), filesize($json_plik));
		$dane = json_decode($tmp, true);
		
		//dodac odczyt innej mapy?
		if( isset($json['run']) )
		{
			//kolizja obiektów, wyjścia poza mape
			$go = true;
			$x=$dane["ja"]["1"]["x"] + $json['run']['x'];
			$y=$dane["ja"]["1"]["y"] + $json['run']['y'];
			
			foreach(array_keys($dane) as $obiekt)
			{
				foreach(array_keys($dane[$obiekt]) as $ktory)
				{
					$tmp = $dane[$obiekt][$ktory];
					
					//wykrywanie kolizji ze ścianami
					if( isset($dane[$obiekt]["wall"]))
					{
						if(isset($tmp["x"]) && isset($tmp["y"]) && $x == $tmp["x"] && $y == $tmp["y"])
						{
							$go = false;
						}
					}
					//Wykrywanie kolizji z potworami
					else
					{
						if($obiekt != "ja" && isset($tmp["x"]) && isset($tmp["y"]) && $x == $tmp["x"] && $y == $tmp["y"])
						{
							$dane["ja"]["1"]["exp"] -= 1;
							unset($dane[$obiekt][$ktory]);
							if($dane["ja"]["1"]["exp"] == 0)
							{
								$dane["ja"]["1"]["lvl"] += 1;
								$dane["ja"]["1"]["exp"] = $dane["ja"]["1"]["lvl"] * 2;
							}
						}
					}
				}
			}
			//czy poza mapą
			if($go && $x >= 0 && $y >= 0 && $x < $dane["size_map"]["x"] && $y < $dane["size_map"]["y"])
			{
				if($dane["ja"]["1"]["x"] != $x || $dane["ja"]["1"]['y'] != $y)
				{
					$dane["tura"] += 1;
					
					//szansa na pojawienie sie nowego potworami
					//wykluczyc kratki tuż przy graczu, dodać maksymalna liczbe potworów aby się czasem nie zapętliło
					$p = rand(0,99);
					$dane["ja"]["1"]["fate"] = $p;
					if ($p<35)
					{
						//losowanie pozycji
						do
						{
							$make_again = false;
							$new_mob = Array( x => rand(0,$dane["size_map"]["x"]), y => rand(0,$dane["size_map"]["y"]));
							$nowy = rand(0,99);
							$dane["ja"]["1"]["mob"] = $new_mob;
							$add = array("monster1","monster2")[rand(0,1)];
							
							//spr. czy pozycja nie jest już zajęta
							// zabiera za dużo zasobów
							/*
							foreach(array_keys($dane) as $obiekt)
							{
								if($make_again)
								{
									break;
								}
								foreach(array_keys($dane[$obiekt]) as $ktory)
								{
									if($ktory == $nowy)
									{
										$make_again = true;
										break;
									}
										
									$tmp = $dane[$obiekt][$ktory];
									if(isset($tmp["x"]) && isset($tmp["y"]) && $new_mob["x"] == $tmp["x"] && $new_mob["x"] == $tmp["y"])
									{
										$make_again = true;
										break;
									}
									$add = $obiekt;
								}
							}
							*/
						}while($make_again);
						//dodanie nowego potwora
						if($add != "")
						{
							$obiekt = $add;
							$dane[$obiekt][$nowy]["x"] = $new_mob["x"];
							$dane[$obiekt][$nowy]["y"] = $new_mob["y"];
						}
					}
					
				}

				$dane["ja"]["1"]["x"] = $x;
				$dane["ja"]["1"]['y'] = $y;
			}
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
<title>Słaba gra</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
  font-size:1.0em;
	}

table {border-collapse: collapse;}
td    {padding: 0px;}


input[type=submit] {
	float:left; /* dla id="control" */
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}


    </style>
	
	
</head>
<body>
Świat: 1<br />
Graczy: 0<br />
Nick: Ja<br />
<div id="lvl">Poziom: 1</div>
<div id="exp">Potrzebne doświadcznie: 0</div>
<div id="tura">Tura: 0</div>

<div id="control">

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

</div>

<br /><br /><br />



<div id="result"></div>

<div id="map"></div>

<div id="debug" style="float:right;">Zmienna treść</div><br />

<br /><br />

<script>

var prawo = {"run":{"x":1,"y":0}};
var lewo = {"run":{"x":-1,"y":0}};
var gora = {"run":{"x":0,"y":-1}};
var dol = {"run":{"x":0,"y":1}};

//var mapa = {"ja":{"x":2,"y":2},"drzewo":{"x":1,"y":1},"drzewo2":{"x":3,"y":3}};
var mapa = {"ja":{"1":{"x":2,"y":2,"exp":5}}, "drzewo":{"wall":"true","1":{"x":1,"y":1},"2":{"x":3,"y":3}},"monster1":{"1":{"x":4,"y":0}},"size_map":{"x":10,"y":10},"tura":0};

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
 $('#lvl').html("Poziom: "+data["ja"]["1"]["lvl"]);
 $('#exp').html("Potrzebne doświadcznie: "+data["ja"]["1"]["exp"]);
 $('#tura').html("Tura: "+data["tura"]);
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
	
	//var obiekty = Object.keys(mapa);
	//var obiekt = Object.keys(mapa)[0];
	
	write('\n<table style="border: 0px solid black; CELLSPACING:0px; aling:center;position: relative;">\n');
	for(var y=0; y<rozmiar_y; y++)
	{
		write('\t<tr>\n');
		for(var x=0; x<rozmiar_x; x++)
		{
			write('\t\t<td>\n');
			write('<img style="" src="images/'+obrazek()+'.gif" /><br />');
			for(var obiekt in mapa)
			{
				for(var ktory in mapa[obiekt])
				{
					if( mapa.hasOwnProperty(obiekt) && y==mapa[obiekt][ktory].y && x==mapa[obiekt][ktory].x)
						write('<div style=""><img style="position: relative; left: 0px; bottom: 0px; margin-top: -100%; display: block;" src="images/'+obiekt+'.gif" /></div>');
				}
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