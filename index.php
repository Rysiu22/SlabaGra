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


    </style>
	
	
</head>
<body>
Nick: Ja<br />
Œwiat: 1<br />
Graczy: 0<br />
Lvl: 1<br />


<script>

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