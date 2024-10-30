<?php

	function bnr_widget_code()
	{
		$values = bnrw_get_value();
		$color = bnrw_random_color();
		
		$html = '	<div class="bnrw-container">
						<div class="bnrw-title">Curs BNR '.date('d-m-Y').'</div>
						<div class="bnrw-values">';
					
		foreach($values as $symbol => $value)
		{
			$html.= '<div class="bnrw-single-value"><span class="bnrw-symbol '.$color.'">'.bnrw_get_symbol($symbol).'</span><span class="bnrw-current-value">'.number_format($value, 2).'</span></div>';
		}
					
		$html.= '		</div>
					</div>';
		return $html;
	}
	
	function bnrw_random_color()
	{
		$colours = array( 'yellow', 'red', 'blue', 'black');
		
		return $colours[array_rand($colours, 1)];
	}
	
	function bnrw_get_symbol($symbol)
	{
		switch($symbol)
		{
			case 'eur': return '&euro;'; break;
			case 'usd': return '&#x24;'; break;
			case 'gbp': return '&pound;'; break;
			
			default: return '&curren;';
		}
	}
	
	function bnrw_get_value()
	{
		$file = BNRW_PATH.'/data/bnr_data.dat';
		
		if(file_exists($file))
		{
			$file = file($file);
			$curs = array();
			
			if($file[0] < strtotime(date('Y-m-d h:i:s')) - 3600)
			{
				bnrw_get_bnr();
				bnrw_get_value();
			}
			else
			{
				$values = parse_bnr_value($file);
				return $values;
			}
		}
		else
		{
			bnrw_get_bnr();
			bnrw_get_value();
		}
	}
	
	function bnrw_get_bnr()
	{
		$url = 'http://www.cursbnr.ro/insert/insertmodule.php?w=182&tc=000000&nodiff&noron&nocb';
		$file = file_get_contents($url);
	
		return get_bnr_values($file);
	}
	
	function get_bnr_values($values)
	{
		$e_offset = explode("1 EUR = ", $values);
		$e_onset = explode("</font>", $e_offset[1]);
		
		$d_offset = explode("1 USD = ", $values);
		$d_onset = explode("</font>", $d_offset[1]);
		
		$euro = str_replace(',', '.', $e_onset[0]);
		$dollar = str_replace(',', '.', $d_onset[0]);
		
		$dataFile = (BNRW_PATH.'/data/bnr_data.dat');
		$valueData = strtotime(date('Y-m-d h:i:s'))."\n".
					"eur;".$euro."\n".
					"usd;".$dollar;
					
		return file_put_contents($dataFile, $valueData);
	}
	
	function parse_bnr_value($file)
	{
		$timestamp = trim($file[0]);
		$values = array();
		
		foreach($file as $key => $value)
		{
			if($key == 0) continue;
			
			$val = explode(";", $value);
			if(isset($val[0]) && !empty($val[0]))
			{
				$values[strtolower($val[0])] = $val[1];
			}
		}
		
		return $values;
		
	}

	//addShortcode

	wp_register_style( 'bnrw_css', plugins_url('bnrw.css', __FILE__) );
	add_shortcode( 'curs_bnr', 'bnr_widget_code' );

	wp_enqueue_style( 'bnrw_css' );
?>