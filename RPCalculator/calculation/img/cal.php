<?php
include ("../../../External/jpgraph/src/jpgraph.php");
include ("../../../External/jpgraph/src/jpgraph_line.php");


class CalHonor{
	private $startRP = 0;
	private $rank = 0;
	private $weeklyRP = 0;
	private $week = 0;
	private $RP = 0;
	private $RPArray;
	
	public function calRP($loop){
		for($i = 0; $i < $loop; $i++){
			$nRP = $this->startRP - ($this->startRP*0.2) + $this->weeklyRP;
			$this->startRP = $nRP;
			$this->RP = $nRP;
			$this->week++;
			$this->RPArray[$i+1] = $nRP;
			//$this->outPut();
		}
	}
	
	public function calRank(){
		return ($this->RP/5000) + 2;
	}
	
	public function outPut(){
		print("Week:".$this->week);
		print("<br />");
		print("Rank Points:".$this->RP);
		print("<br />");
		print("Rank:".$this->calRank());
		print("<br />");
		print("-------------------------------------------------------------");
		print("<br />");
	}
	
	public function addGraph($startRP){
		// Die Werte der 2 Linien in ein Array speichern
		$ydata = $this->RPArray;

		// Grafik generieren und Grafiktyp festlegen
		$graph = new Graph(1200,800,"auto");    
		$graph->SetScale("textlin", $startRP, 61000);
		$graph->yscale->ticks->Set(((61000-$startRP)/18),2);

		// Die Zwei Linien generieren
		$lineplot=new LinePlot($ydata);

		// Die Linien zu der Grafik hinzufügen
		$graph->Add($lineplot);

		// Grafik Formatieren
		$graph->img->SetMargin(60,20,20,50);
		$graph->xaxis->title->Set("Weeks - 1");
		$graph->yaxis->title->Set("Rank Points");

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

		$lineplot->SetColor("blue");
		$lineplot->SetWeight(2);

		// Specify marks for the line plots
		$lineplot->mark->SetType(MARK_FILLEDCIRCLE);
		$lineplot->mark->SetFillColor("red");
		$lineplot->mark->SetWidth(4);

		$graph->yaxis->SetColor("red");
		$graph->yaxis->SetWeight(2);
		$graph->SetShadow();

		// Grafik anzeigen
		$graph->Stroke();	
	}
	
	public function __construct($startRP, $weeklyRP, $loop){
		
		$this->RPArray[0] = $startRP;
		$this->startRP = $startRP;
		$this->weeklyRP = $weeklyRP;
		$this->calRP($loop);
		
		$this->addGraph($startRP);
	}
}
new CalHonor($_GET["startrp"], $_GET["rpweek"], 10);
?>