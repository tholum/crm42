<?PHP
class gauge
{
  function gauge()
  {
		// Set parameters
    $this->imagesize = 100;
    $this->span      = 180;
		$this->min       = 0;
		$this->max       = 100;
		$this->maj_tspan = 10;
		$this->min_tspan = 5;
		$this->maxgreen  = 80;
		$this->maxyellow = 90;
		$this->legend    = "";
		$this->pos       = 0;
    $this->majortick = true;
  }

	function setPos($pos)
  {
		// Only draw up to +-0.5% of the gauge scale
    if ($pos < $this->min+($this->max*0.005))
    {
      $pos = $this->min+($this->max*0.005);
    }
		if ($pos > $this->max-($this->max*0.005))
    {
      $pos = $this->max-($this->max*0.005);
    }

		$this->pos = $pos;
	}

	function setLegend($legend)
  {
		$this->legend = $legend;
	}

	function setGreen($green)
  {
		$this->maxgreen = $green;
	}

	function setYellow($yellow)
  {
		$this->maxyellow = $yellow;
	}

	function setMin($min)
  {
		$this->min = $min;
	}

	function setMax($max)
  {
		$this->max = $max;
	}

  function setSpan($span)
  {
    $this->span = $span;
  }

  function setImagesize($size)
  {
    $this->imagesize = $size;
  }

	function plot()
  {
    if ( $this->max - $this->min > 50 )
    {
      $this->minortick = true;
    }
    else
    {
      $this->minortick = false;
    }

    if ($this->maxyellow <= $this->max)
    {
   		$this->maxred = $this->max;
    }
		$this->center    = $this->imagesize/2;
    $this->offset    = ( 360 - $this->span )/2;
    $this->startarc  = 90+$this->offset;
    $this->endarc    = 90-$this->offset;

		// Prepare image
    error_log("Size = ".$this->imagesize);
		$this->img    = imagecreate($this->imagesize, $this->imagesize);
    $this->white  = imagecolorallocate($this->img, 255, 255, 255);
    $this->black  = imagecolorallocate($this->img, 0, 0, 0);
    $this->silver = imagecolorallocate($this->img, 0xD0, 0xD0, 0xD0);
    $this->red    = imagecolorallocate($this->img, 255, 0, 0);
    $this->yellow = imagecolorallocate($this->img, 255, 165, 0);
    $this->green  = imagecolorallocate($this->img, 0, 120, 0);

		// Start Drawing Gauge
		imagearc($this->img, $this->center, $this->center, $this->imagesize*99/100, $this->imagesize*99/100, 0, 360, $this->black);

    // Draw Green Arc
    if ($this->maxgreen > $this->min)
    {
      if ($this->maxgreen > $this->max)
      {
        $this->maxgreen = $this->max;
      }
      $this->startgreen = $this->startarc;
      $this->endgreen   = $this->startarc + floor( $this->span * ($this->maxgreen/$this->max));
      imagefilledarc($this->img, $this->center, $this->center, 0.9*$this->imagesize, 0.9*$this->imagesize, $this->startgreen, $this->endgreen, $this->green, IMG_ARC_PIE );
    }
    else
    {
      // No green in this gauge.
      $this->maxgreen = $this->min;
    }

    // Draw Yellow Arc
    if ($this->maxyellow > $this->maxgreen)
    {
      if ($this->maxyellow > $this->max)
      {
        $this->maxyellow = $this->max;
      }
      $this->startyellow = $this->endgreen;
      $this->endyellow   = $this->startarc + floor( $this->span * ($this->maxyellow/$this->max));
      imagefilledarc($this->img, $this->center, $this->center, 0.9*$this->imagesize, 0.9*$this->imagesize, $this->startyellow, $this->endyellow, $this->yellow, IMG_ARC_PIE );
    }
    else
    {
      // No yellow in this
      $this->maxyellow = $this->maxgreen;
    }

    // Draw red Arc
    if ($this->maxred > $this->maxyellow)
    {
      if ($this->maxred > $this->max)
      {
        $this->maxred = $this->max;
      }
      $this->startred = $this->endyellow;
      $this->endred   = $this->startarc + floor( $this->span * ($this->maxred/$this->max));
      imagefilledarc($this->img, $this->center, $this->center, 0.9*$this->imagesize, 0.9*$this->imagesize, $this->startred, $this->endred, $this->red, IMG_ARC_PIE );
    }
    else
    {
      // No red in this
      $this->maxred = $this->maxyellow;
    }
    imagefilledarc($this->img, $this->center, $this->center, 0.6*$this->imagesize, 0.6*$this->imagesize, 0, 360, $this->white, IMG_ARC_PIE );

    // Set scale
    imagearc($this->img, $this->center, $this->center, 0.75*$this->imagesize, 0.75*$this->imagesize, $this->startarc, $this->endarc, $this->black);
    // set minor tickmarks
    if ( $this->minortick )
    {
      for ($i=$this->min; $i<=$this->max; $i+=$this->min_tspan)
      {
        $this->addtickmark("MINOR", $i);
      }
    }
    // set major tickmarks
    if ( $this->majortick )
    {
      for ($i=$this->min; $i<=$this->max; $i+=$this->maj_tspan)
      {
        $this->addtickmark("MAJOR", $i);
      }
    }

    // draw needle
    $long    = 0.95*$this->center;
    $width   = 0.06*$this->center;
    $width2  = 0.2*$this->center;
    $degrees = $this->startarc + floor($this->pos*$this->span/$this->max);
    $triangle = array($this->center,
                      $this->center,
                      $this->center+$width*cos(deg2rad($degrees+90)),
                      $this->center+$width*sin(deg2rad($degrees+90)),
                      $this->center+$long*cos(deg2rad($degrees)),
                      $this->center+$long*sin(deg2rad($degrees)));
    imagefilledpolygon($this->img, $triangle, 3, $this->black);

    $triangle = array($this->center,
                      $this->center,
                      $this->center+$width*cos(deg2rad($degrees-90)),
                      $this->center+$width*sin(deg2rad($degrees-90)),
                      $this->center+$long*cos(deg2rad($degrees)),
                      $this->center+$long*sin(deg2rad($degrees)));
    imagefilledpolygon($this->img, $triangle, 3, $this->silver);

    $triangle = array($this->center,
                      $this->center,
                      $this->center+$width*cos(deg2rad($degrees-90)),
                      $this->center+$width*sin(deg2rad($degrees-90)),
                      $this->center+$width2*cos(deg2rad($degrees-180)),
                      $this->center+$width2*sin(deg2rad($degrees-180)));
    imagefilledpolygon($this->img, $triangle, 3, $this->black);

    $triangle = array($this->center,
                      $this->center,
                      $this->center+$width*cos(deg2rad($degrees+90)),
                      $this->center+$width*sin(deg2rad($degrees+90)),
                      $this->center+$width2*cos(deg2rad($degrees-180)),
                      $this->center+$width2*sin(deg2rad($degrees-180)));
    imagefilledpolygon($this->img, $triangle, 3, $this->silver);

    imagefilledarc($this->img, $this->center, $this->center, 0.1*$this->center, 0.1*$this->center, 0, 360, $this->black, IMG_ARC_PIE );

		//Wait for plot to avoid drawing more than one legend or hand
    imagestring($this->img, 3, $this->center+1-strlen($this->legend)*7/2, 0.58*$this->imagesize, $this->legend, $this->black);
 		header("Content-type: image/gif");
		imagegif($this->img);
		imagedestroy($this->img);
	}

	function addtickmark($type, $value)
  {
		// Draw black divisions
    $degrees = $this->startarc + floor($value*$this->span/$this->max);
    switch ($type)
    {
      case "MAJOR":
        $y  = 0.85*$this->center*sin(deg2rad($degrees));
        $x  = 0.85*$this->center*cos(deg2rad($degrees));
        $y2 = 0.65*$this->center*sin(deg2rad($degrees));
        $x2 = 0.65*$this->center*cos(deg2rad($degrees));
        break;

      case "MINOR":
        $y  = 0.80*$this->center*sin(deg2rad($degrees));
        $x  = 0.80*$this->center*cos(deg2rad($degrees));
        $y2 = 0.70*$this->center*sin(deg2rad($degrees));
        $x2 = 0.70*$this->center*cos(deg2rad($degrees));
        break;
    }
    imageline ($this->img, $this->center+$x2, $this->center+$y2, $this->center+$x, $this->center+$y, $this->black);
	}

}

?>
