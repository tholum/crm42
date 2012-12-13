<?php
class ConvertLength
{
	var $from;
	var $to;
	var $value;
	function convert($from,$to,$value) {
		$this->from=$from;
		$this->to=$to;
		$this->value=$value;
		
		switch($this->from){
			case 'Inches':if($this->to=="Inches")
						$total=$this->value*1;
						if($this->to=="Feet")
						$total=$this->value*0.083333;
						if($this->to=="Yards")
						$total=$this->value*0.027778;
						if($this->to=="Meters")
						$total=$this->value*0.0254;
						if($this->to=="Millimeters")
						$total=$this->value*25.4;
						break;
			
			case 'Feet':if($this->to=="Inches")
						$total=$this->value*12;
						if($this->to=="Feet")
						$total=$this->value*1;
						if($this->to=="Yards")
						$total=$this->value*0.33333;
						if($this->to=="Meters")
						$total=$this->value*0.3048;
						if($this->to=="Millimeters")
						$total=$this->value*304.8;
						break;
			
			case 'Yards':   if($this->to=="Inches")
							$total=$this->value*36;
							if($this->to=="Feet")
							$total=$this->value*3;
							if($this->to=="Yards")
							$total=$this->value*1;
							if($this->to=="Meters")
							$total=$this->value*0.9144;
							if($this->to=="Millimeters")
							$total=$this->value*914.4;
							break;
			
			case 'Meters':  if($this->to=="Inches")
							$total=$this->value*39.3707;
							if($this->to=="Feet")
							$total=$this->value*3.2808399;
							if($this->to=="Yards")
							$total=$this->value*1.094;
							if($this->to=="Meters")
							$total=$this->value*1;
							if($this->to=="Millimeters")
							$total=$this->value*1000;
							break;
			
			case 'Millimeters':  if($this->to=="Inches")
						$total=$this->value*0.0393707;
						if($this->to=="Feet")
						$total=$this->value*0.0032808399;
						if($this->to=="Yards")
						$total=$this->value*0.001094;
						if($this->to=="Meters")
						$total=$this->value*0.001;
						if($this->to=="Millimeters")
						$total=$this->value*1;
						break;
			}
			
			return $total;
}
}
?>