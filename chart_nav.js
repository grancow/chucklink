google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable();
		data.addColumn('string', 'Data');
		data.addColumn('number', 'Wszystkie');
		data.addRows([
		["2018-03-10",157],
		["2018-03-11",181],
		["2018-03-12",201],
		["2018-03-13",218],
		["2018-03-14",239],
		["2018-03-15",252]
		]);

	
/*		      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Składniki');
      data.addColumn('number', 'Ilość');
      data.addRows([
        ['kalafior', 3],
        ['cebula', 1],
        ['oliwki', 1], 
        ['papryka', 1],
        ['szynka', 2]
      ]); 
		
		
		var myVar = "Multidimensional array test; ";
a = new Array(4);
for (var i = 0; i < 4; i++) {
   a[i] = new Array(4);
   for (var j = 0; j < 4; j++) {
      a[i][j] = "[" + i + "," + j + "]";
   }
}
for (var i = 0; i < 4; i++) {
   str = "Row " + i + ":";
   for (var j = 0; j < 4; j++) {
      str += a[i][j];
   }
		
		
	*/	
		
		
		
		var options = {
		legend: 'none',
		width:1170,
		height	:500,
		colors: ['#2308f1', '#097138', '#a52714', '#f6ff09', '#dd0aea', '#06d4f9', '#ffc521', '#9b00ed', '#36b03c' ],
		chartArea:{left:100,top:50,width:'80%',height:'80%'}
		//legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }


var nav0Active = "5px solid #2308f1";
var nav1Active = "5px solid #097138";
var nav2Active = "5px solid #a52714";
var nav3Active = "5px solid #f6ff09";
var nav4Active = "5px solid #dd0aea";
var nav5Active = "5px solid #06d4f9";
var nav6Active = "5px solid #ffc521";
var nav7Active = "5px solid #9b00ed";
var nav8Active = "5px solid #36b03c";

function nav0Click()
{
var nav0 = document.getElementById("nav0");
var tempTekst = nav0.style.borderBottom;
nav0.style.borderBottom = nav0Active;
var info = document.getElementById("info");
//info.innerHTML += tempTekst + "<br>";
var nav0On;
if (tempTekst != "5px solid #2308f1")
{
//	  info.innerHTML += nav0Active + "<br>";
	  nav0On = true;
}
else {nav0On = false;}
nav0Active = tempTekst;

var str = "";
str = "Tutaj powinna być zmienna ile " + ile;
str += "<br />" ;
str += "a 2 pierwsze wartości dla pierwszego id tabeli chart_table wynosi: " + chart_table[1][0] + " " + chart_table[1][1] + "<br />";
str += "a wartość elementu nav0On wynosi: " + nav0On + "<br />" ;

var data = document.getElementById("info");
data.innerHTML = str ; 


return nav0On;
};

function nav1Click()
{
var nav1 = document.getElementById("nav1");
var tempTekst = nav1.style.borderBottom;
nav1.style.borderBottom = nav1Active;
nav1Active = tempTekst;
};

function nav2Click()
{
var nav2 = document.getElementById("nav2");
var tempTekst = nav2.style.borderBottom;
nav2.style.borderBottom = nav2Active;
nav2Active = tempTekst;
};

function nav3Click()
{
var nav3 = document.getElementById("nav3");
var tempTekst = nav3.style.borderBottom;
nav3.style.borderBottom = nav3Active;
nav3Active = tempTekst;
};

function nav4Click()
{
var nav4 = document.getElementById("nav4");
var tempTekst = nav4.style.borderBottom;
nav4.style.borderBottom = nav4Active;
nav4Active = tempTekst;
};

function nav5Click()
{
var nav5 = document.getElementById("nav5");
var tempTekst = nav5.style.borderBottom;
nav5.style.borderBottom = nav5Active;
nav5Active = tempTekst;
};

function nav6Click()
{
var nav6 = document.getElementById("nav6");
var tempTekst = nav6.style.borderBottom;
nav6.style.borderBottom = nav6Active;
nav6Active = tempTekst;
};

function nav7Click()
{
var nav7 = document.getElementById("nav7");
var tempTekst = nav7.style.borderBottom;
nav7.style.borderBottom = nav7Active;
nav7Active = tempTekst;
};

function nav8Click()
{
var nav8 = document.getElementById("nav8");
var tempTekst = nav8.style.borderBottom;
nav8.style.borderBottom = nav8Active;
nav8Active = tempTekst;
};


