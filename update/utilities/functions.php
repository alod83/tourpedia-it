<?php

function get_province($province)
{
	$province = strtoupper($province);
	switch($province)
	{
		// Friuli
		case "GORIZIA":
			return "GO";
		case "UDINE":
			return "UD";
		case "PORDENONE":
			return "PN";
		case "TRIESTE":
			return "TS";
		// Liguria
		case "GENOVA":
			return "GE";
		case "IMPERIA":
			return "IM";
		case "LA SPEZIA":
			return "SP";
		case "SAVONA":
			return "SV";
		// Piemonte
		case "ALESSANDRIA":
			return "AL";
		case "ASTI":
			return "AT";
		case "BIELLA":
			return "BI";
		case "CUNEO":
			return "CN";
		case "TORINO":
			return "TO";
		case "VERBANO-CUSIO-OSSOLA":
			return "VB";
		case "VERCELLI":
			return "VC";
		// Puglia
		case "BARI":
			return "BA";
		case "BARLETTA-ANDRIA-TRANI":
			return "BT";
		case "BRINDISI":
			return "BR";
		case "FOGGIA":
			return "FG";
		case "LECCE":
			return "LE";
		case "TARANTO":
			return "TA";
		// Veneto
		case "BELLUNO":
			return "BL";
		case "PADOVA":
			return "PD";
		case "ROVIGO":
			return "RO";
		case "TREVISO":
			return "TV";
		case "VENEZIA":
			return "VE";
		case "VERONA":
			return "VE";
		case "VICENZA":
			return "VI";
	}

}
function get_record(&$document,$mapping,$arr)
{
	foreach($mapping as $k => $v)
	{
		$value = "";
		if(strpos($v, ',') !== false)
		{
			$va = explode(',',$v);
			foreach($va as $kva)
				$value .= " ".$arr[intval($kva)];
			$value = trim($value);
		}
		else if(strpos($v, '/') !== false)
		{
			$va = explode('/',$v);
			
			$value = str_replace('.', ',',$arr[intval($va[0])]);
			$div = $va[1];
			$value = floatval($value)/$div;
		}
		else if(strpos($v, '<') !== false)
		{
			$va = explode('<', $v);
			$value = intval($va[0]);
			switch($va[1])
			{
				case 'province': 
					$value = get_province($arr[$value]);
					break;
			}
		}
		else
			$value = $arr[intval($v)];
		
		switch($k)
		{
			case 'postal-code'	: 
			case 'rooms'		: 
			case 'beds'			: 
			case 'toilets'		:
			case 'suites'		:
				$document[$k] = intval($value);
				break;
			case 'latitude'		: 
			case 'longitude'	:
				$document[$k]=round(floatval($value),6);
				break;
			default				:
				$document[$k] = $value;
		}
	}
}
?>