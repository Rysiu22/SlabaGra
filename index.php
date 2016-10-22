<html>
<head>
<title>Pse³do gra</title>

<style>

#map {
	display: flex;
	justify-content: center;
	}

table {border-collapse: collapse;}
td    {padding: 0px;}




      #rodzic {
        background-color:black;
        font-size:1.4em;
		margin-right: auto; margin-left: auto;
      }

      #rodzic:after {
        content:'';
        display:block;
		text-align: center;
      }

	  
#dziecko1 {
  float:left;
	white-space: nowrap; 
overflow: hidden; 
text-overflow: ellipsis;
  margin-bottom:0px;
  background-color:lightblue;
}

#dziecko2 {
  float:left;
  margin-bottom:0px;
  background-color:lightgreen;
}

#dziecko3 {
  float:left;
  margin-bottom:0px;
  background-color:lightgreen;
}

#dziecko4 {
  clear:both;
      position: fixed;
  float:left;
  margin-bottom:0px;
  background-color:lightblue;
}

#dziecko5 {
  float:left;
  margin-bottom:0px;
  background-color:lightgreen;
}

#dziecko6 {
  float:left;
  margin-bottom:0px;
  background-color:lightgreen;
}
    </style>
	
	
</head>
<body>
Nick: Ja<br />
Œwiat: 1<br />
Graczy: 0<br />
Lvl: 1<br />

<div>
<!-- START http://forum.webhelp.pl/poczatkujacy-webmaster/obrazki-obok-siebie-t56595.html 
<div style='float:left'>
<img src='images/1.gif' >
</div>
<div style='position:absolute; left:50%'>
<img src='images/2.gif'>
</div>
<div style='float:right'>
<img src='images/3.gif' style='float:right'>
</div>
<!-- END http://forum.webhelp.pl/poczatkujacy-webmaster/obrazki-obok-siebie-t56595.html -->
</div>

<!--
<script>

function rysuj_mape(rozmiar_x, rozmiar_y, dostepne_kafelki)
{
	document.write('<div id="rodzic">');

	for(var i=0; i<rozmiar_y*rozmiar_x; i++)
	{
		if( i != 0 && i % rozmiar_x == 0)
			document.write('<div id="dziecko4"><img src="images/'+((i%dostepne_kafelki)+1)+'.gif" ></div>');
		else
			document.write('<div id="dziecko1"><img src="images/'+((i%dostepne_kafelki)+1)+'.gif" ></div>');
	}
	document.write('</div>');
}

rysuj_mape(10, 5, 9);
</script>
-->
 

<script>
/*
var keys = [];

for (var key in driversCounter) {
    if (driversCounter.hasOwnProperty(key)) {
        keys.push(key);
    }
}
*/

var mapa = {"ja":{"x":2,"y":2},"drzewo":{"x":1,"y":1}};

function rysuj_mape(rozmiar_x, rozmiar_y, dostepne_kafelki)
{
	var a=0;
	//function obrazek(){return (a++%dostepne_kafelki)+1;}
	function obrazek(){return 0;}
	
	var obiekty = Object.keys(mapa);
	var obiekt = Object.keys(mapa)[0];
	
	document.write('<div id="map">\n<table style="border: 0px solid black; CELLSPACING:0px; aling:center;">\n');
	for(var y=0; y<rozmiar_y; y++)
	{
		document.write('\t<tr>\n');
		for(var x=0; x<rozmiar_x; x++)
		{
			document.write('\t\t<td>\n');
			document.write('<img style="" src="images/'+obrazek()+'.gif" /><br />');
			for(var obiekt in mapa)
			{
				if( mapa.hasOwnProperty(obiekt) && y==mapa[obiekt].y && x==mapa[obiekt].x)
					document.write('<img style="position: relative; left: 0px; bottom: 0px; margin-top: -100%;" src="images/'+obiekt+'.gif" alt="..." />');
			}
			document.write('\t\t</td>\n');
					
		}
		document.write('\t</tr>\n');
	}
	document.write('</table>\n</div> ');
}

rysuj_mape(5, 5, 0);
</script>



</body>
</html>