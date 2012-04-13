<?php
/**
 * Smarty plugin "Thumb" creates cached thumbnails
 * 
 * @copyright (C) 2005 Christoph Erdmann http://www.cerdmann.com/thumb/
 * 
 * @license This library is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 * You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA 
 * 
 * @author Christoph Erdmann (CE) http://www.cerdmann.com
 * @author Benjamin Fleckenstein (BF) http://www.benjaminfleckenstein.de
 * @author Marcus Gueldenmeister (MG) http://www.gueldenmeister.de/marcus/
 * @author Andreas Bösch (AB)
 * @author Jason Hinkle (JH) http://www.verysimple.com/
 * 
 * @param hint true/false to show magnifying glass overlay
 * @param link true/false to link to original image
 * @param extrapolate true/false to enlarge image if necessary
 * @param cache file path to writable directory for cache files
 * @param dev set to true to overlay the rendering time on the image
 * 
 * @version:
 * 2012-01-05 Friendly output when file type is not supported such as pdf, swf, etc (JH)
 * 2010-10-03 Friendly output when file size is empty due to unknown error (JH)
 * 2008-03-13 set default param values to avoid various warnings in strict mode
              error handling when cache is not writable
              changed empty to !isset to make params work properly
              if cache path is a full uri, strip it out in the generated html code (JH)
 * 2007-01-07 Friendly output when file doesn't exist and/or is blank (JH)
 * 2005-10-31 Fixed some small bugs (CE)
 * 2005-10-09 Rewrote crop-function (CE)
 * 2005-10-08 Decreased processing time by prescaling linear and cleaned code (CE)
 * 2005-07-13 Set crop=true as standard (CE)
 * 2005-07-12 Added crop parameter. Original code by "djneoform at gmail dot com" (AB)
 * 2005-07-02 Found a stupid mistake. Should be faster now (CE)
 * 2005-06-02 Added file_exists(SOURCE)-trigger (CE)
 * 2005-06-02 Added extrapolate parameter (CE)
 * 2005-06-12 Bugfix alt/title (MG)
 * 2005-06-10 Bugfix (MG)
 * 2005-06-02 Added window parameter (MG)
 * 2005-06-02 Made grey banner configurable, added possibility to keep format in thumbs
			  made cache path changeable (BF & MG)
 * 2004-12-01 New link, hint, quality and type parameter (CE)
 * 2004-12-02 Intergrated UnsharpMask (CE)
 * -------------------------------------------------------------
 */
 function smarty_function_thumb($params, &$smarty)
	{

	// prevents warning in strict mode (JH)
	if (!isset($params['width'])) $params['width'] = null;
	if (!isset($params['height'])) $params['height'] = null;
	if (!isset($params['longside'])) $params['longside'] = null;
	if (!isset($params['shortside'])) $params['shortside'] = null;
	if (!isset($params['html'])) $params['html'] = null;
	if (!isset($params['sharpen'])) $params['sharpen'] = null;
	if (!isset($params['hint'])) $params['hint'] = null;
	if (!isset($params['addgreytohint'])) $params['addgreytohint'] = null;
	
	if (!isset($params['link'])) $params['link'] = true;
	if (!isset($params['window'])) $params['window'] = true;
	if (!isset($params['hint'])) $params['hint'] = true;
	if (!isset($params['extrapolate'])) $params['extrapolate'] = true;
	if (!isset($params['dev'])) $params['dev'] = false;
	if (!isset($params['crop'])) $params['crop'] = true;
	if (!isset($params['width']) && !isset($params['height']) 
			&& !isset($params['longside']) && !isset($params['shortside'])) $params['width'] = 100;

	// have a look at the params for debugging purposes
	// return "<pre>" . print_r($params,1) . "</pre>";
	
	// defining these prevents warnings
	$_SRC = array();
	$_DST = array();
	$_DST['offset_w'] = 0;
	$_DST['offset_h'] = 0;
	
	// Start time measurement
	if ($params['dev'])
		{
		if (!function_exists('getmicrotime'))
			{
			function getmicrotime()
				{
				list($usec, $sec) = explode(" ",microtime());
				return ((float)$usec + (float)$sec);
				}
			}
		$time['start'] = getmicrotime();
		}
		
	// Funktion zum Schärfen
	if (!function_exists('UnsharpMask'))
		{
		// Unsharp mask algorithm by Torstein Hønsi 2003 (thoensi_at_netcom_dot_no)
		// Christoph Erdmann: changed it a little, cause i could not reproduce the darker blurred image, now it is up to 15% faster with same results
		function UnsharpMask($img, $amount, $radius, $threshold)
			{
			// Attempt to calibrate the parameters to Photoshop:
			if ($amount > 500) $amount = 500;
			$amount = $amount * 0.016;
			if ($radius > 50) $radius = 50;
			$radius = $radius * 2;
			if ($threshold > 255) $threshold = 255;
	
			$radius = abs(round($radius)); 	// Only integers make sense.
			if ($radius == 0) {	return $img; imagedestroy($img); break;	}
			$w = imagesx($img); $h = imagesy($img);
			$imgCanvas = $img;
			$imgCanvas2 = $img;
			$imgBlur = imagecreatetruecolor($w, $h);
	
			// Gaussian blur matrix:
			//	1	2	1		
			//	2	4	2		
			//	1	2	1		

			// Move copies of the image around one pixel at the time and merge them with weight
			// according to the matrix. The same matrix is simply repeated for higher radii.
			for ($i = 0; $i < $radius; $i++)
				{
				imagecopy	  ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right
				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
				imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down
				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
				}
			$imgCanvas = $imgBlur;	
				
			// Calculate the difference between the blurred pixels and the original
			// and set the pixels
			for ($x = 0; $x < $w; $x++)
				{ // each row
				for ($y = 0; $y < $h; $y++)
					{ // each pixel
					$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
					$rOrig = (($rgbOrig >> 16) & 0xFF);
					$gOrig = (($rgbOrig >> 8) & 0xFF);
					$bOrig = ($rgbOrig & 0xFF);
					$rgbBlur = ImageColorAt($imgCanvas, $x, $y);
					$rBlur = (($rgbBlur >> 16) & 0xFF);
					$gBlur = (($rgbBlur >> 8) & 0xFF);
					$bBlur = ($rgbBlur & 0xFF);

					// When the masked pixels differ less from the original
					// than the threshold specifies, they are set to their original value.
					$rNew = (abs($rOrig - $rBlur) >= $threshold) ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) : $rOrig;
					$gNew = (abs($gOrig - $gBlur) >= $threshold) ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) : $gOrig;
					$bNew = (abs($bOrig - $bBlur) >= $threshold) ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) : $bOrig;
					
					if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew))
						{
						$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
						ImageSetPixel($img, $x, $y, $pixCol);
						}
					}
				}
			return $img;
			}
		}

	$_CONFIG['types'] = array('','.gif','.jpg','.png');


	### Übergebene Parameter auswerten und verifizieren
	if (empty($params['cache'])) $_CONFIG['cache'] = 'images/cache/';
	else $_CONFIG['cache'] = $params['cache'];
	
	//if (empty($params['file']) OR !file_exists($params['file'])) { $smarty->_trigger_fatal_error("thumb: parameter 'file' cannot be empty and must exist");	return;	}
	
	if (empty($params['file'])) { 
		return "<div class='warning'>thumb: parameter 'file' cannot be empty is required and cannot be blank</div>";	
	}
	if (!file_exists($params['file'])) { 
		return "<div class='warning'>thumb: file '".$params['file']."' could not be found</div>";	
	}
	
	### Info über Source (SRC) holen
	$temp = getimagesize($params['file']);

	$_SRC['file']		= $params['file'];
	$_SRC['width']		= $temp[0];
	$_SRC['height']		= $temp[1];
	$_SRC['type']		= $temp[2]; // 1=GIF, 2=JPG, 3=PNG, SWF=4
	$_SRC['string']		= $temp[3];
	$_SRC['filename'] 	= basename($params['file']);
	$_SRC['modified'] 	= filemtime($params['file']);

	// Hash erstellen
	$_SRC['hash'] 		= md5($_SRC['file'].$_SRC['modified'].implode('',$params));
	
	if (empty($_SRC['type'])) return "<div class='warning'>thumb: unsupported image type</div>";
	
	// image types: 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(orden de bytes intel), 8 = TIFF(orden de bytes motorola), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
	if ($_SRC['type'] > 3) return "<div class='warning'>thumb: image type is not supported.  supported types are GIF, JPG and PNG</div>";
	
	### Infos über Destination (DST) errechnen
	if (is_numeric($params['width'])) $_DST['width'] = $params['width'];
	else 
	{
		if ($_SRC['height'] == 0 || $_SRC['width'] == 0 ) return "<div class='warning'>thumb: illegal width/height value</div>";
		$_DST['width'] = round($params['height']/($_SRC['height']/$_SRC['width']));
	}

	if (is_numeric($params['height'])) $_DST['height']	= $params['height'];
	else 
	{
		if ($_SRC['height'] == 0 || $_SRC['width'] == 0 ) return "<div class='warning'>thumb: illegal width/height value</div>";
		$_DST['height'] = round($params['width']/($_SRC['width']/$_SRC['height']));
	}
	
	// Das Größenverhältnis soll erhalten bleiben egal ob das Bild hoch oder querformatig ist.
	if (is_numeric($params['longside']))
		{
		if ($_SRC['width'] < $_SRC['height']) 
			{
			$_DST['height']	= $params['longside'];
			$_DST['width']	= round($params['longside']/($_SRC['height']/$_SRC['width']));
			}
		else
			{
			$_DST['width']	= $params['longside'];
			$_DST['height']	= round($params['longside']/($_SRC['width']/$_SRC['height']));
			}
		}
	elseif (is_numeric($params['shortside']))
		{
		if ($_SRC['width'] < $_SRC['height']) 
			{
			$_DST['width']	= $params['shortside'];
			$_DST['height']	= round($params['shortside']/($_SRC['width']/$_SRC['height']));
			}
		else
			{
			$_DST['height']	= $params['shortside'];
			$_DST['width']	= round($params['shortside']/($_SRC['height']/$_SRC['width']));
			}
		}

	// Soll beschnitten werden? (Standard)
	if($params['crop'])
		{							
		$width_ratio = $_SRC['width']/$_DST['width'];
		$height_ratio = $_SRC['height']/$_DST['height'];
		
		// Es muss an der Breite beschnitten werden
		if ($width_ratio > $height_ratio)
			{
			$_DST['offset_w'] = round(($_SRC['width']-$_DST['width']*$height_ratio)/2);
			$_SRC['width'] = round($_DST['width']*$height_ratio);
			}
		// es muss an der Höhe beschnitten werden
		elseif ($width_ratio < $height_ratio)
			{
			$_DST['offset_h'] = round(($_SRC['height']-$_DST['height']*$width_ratio)/2);
			$_SRC['height'] = round($_DST['height']*$width_ratio);
			}
		}

	// Wenn das Ursprungsbild kleiner als das Ziel-Bild ist, soll nicht hochskaliert werden und die neu berechneten Werte werden wieder überschrieben
	if ( $params['extrapolate'] != true && $params['extrapolate'] != "true"  && $_DST['height'] > $_SRC['height'] && $_DST['width'] > $_SRC['width'])
		{
		$_DST['width'] = $_SRC['width'];
		$_DST['height'] = $_SRC['height'];
		}
		
	if (!empty($params['type'])) $_DST['type']	= $params['type'];
	else $_DST['type']	= $_SRC['type'];

//	if ( empty($_CONFIG['cache']) || empty($_SRC['hash']) || empty($_CONFIG['types']) || empty($_DST['type']))
//	{
//		
//	}
	
	$_DST['file']		= $_CONFIG['cache'].$_SRC['hash'].$_CONFIG['types'][$_DST['type']];
	$_DST['string']		= 'width="'.$_DST['width'].'" height="'.$_DST['height'].'"';


	// strip out any path stuff in case the cache path is a full URI (JH)
	$file_url = str_replace( realpath(".") ,".",$_DST['file']);
	$file_url = str_replace(array("\\","//"),array("/","/"), $file_url);
	
	### Rückgabe-Strings erstellen

	if (empty($params['html'])) $_RETURN['img'] = '<img src="'.$file_url.'" '.$params['html'].' '.$_DST['string'].' alt="" title="" />';
	else $_RETURN['img'] = '<img src="'.$file_url.'" '.$params['html'].' '.$_DST['string'].' />';

	if ($params['link'] == "true")
		{
		if (empty($params['linkurl'])) $params['linkurl'] = $file_url;
		
		if ($params['window'] == "true") $returner = '<a href="'.$file_url.'" target="_blank">'.$_RETURN['img'].'</a>';
		else $returner = '<a href="'.$file_url.'">'.$_RETURN['img'].'</a>';
		}
	else
		{
		$returner = $_RETURN['img'];
		}
	
	### Cache-Datei abfangen
	if (file_exists($_DST['file']) AND !$params['dev']) return $returner;
	
	
	### ansonsten weitermachen
	
	// SRC einlesen
	if ($_SRC['type'] == 1)	$_SRC['image'] = imagecreatefromgif($_SRC['file']);
	if ($_SRC['type'] == 2)	$_SRC['image'] = imagecreatefromjpeg($_SRC['file']);
	if ($_SRC['type'] == 3)	$_SRC['image'] = imagecreatefrompng($_SRC['file']);

	// Wenn das Bild sehr groß ist, zuerst linear auf vierfache Zielgröße herunterskalieren und $_SRC überschreiben
	if ($_DST['width']*4 < $_SRC['width'] AND $_DST['height']*4 < $_SRC['height'])
		{
		// Multiplikator der Zielgröße
		$_TMP['width'] = round($_DST['width']*4);
		$_TMP['height'] = round($_DST['height']*4);
		
		$_TMP['image'] = imagecreatetruecolor($_TMP['width'], $_TMP['height']);
		imagecopyresized($_TMP['image'], $_SRC['image'], 0, 0, $_DST['offset_w'], $_DST['offset_h'], $_TMP['width'], $_TMP['height'], $_SRC['width'], $_SRC['height']);
		$_SRC['image'] = $_TMP['image'];
		$_SRC['width'] = $_TMP['width'];
		$_SRC['height'] = $_TMP['height'];
		
		// Wenn vorskaliert wird, darf ja nicht nochmal ein bestimmter Bereich ausgeschnitten werden
		$_DST['offset_w'] = 0;
		$_DST['offset_h'] = 0;
		unset($_TMP['image']);
		}

	// DST erstellen
	$_DST['image'] = imagecreatetruecolor($_DST['width'], $_DST['height']);
	imagecopyresampled($_DST['image'], $_SRC['image'], 0, 0, $_DST['offset_w'], $_DST['offset_h'], $_DST['width'], $_DST['height'], $_SRC['width'], $_SRC['height']);
	if ($params['sharpen'] != "false") $_DST['image'] = UnsharpMask($_DST['image'],80,.5,3);

	// Soll eine Lupe eingefügt werden?
	if ($params['hint'] == "true" AND $params['link'] == "true")
		{
		//Soll der weiße Balken wirklich hinzugefügt werden?
		if ($params['addgreytohint'] != 'false')
			{
			$trans = imagecolorallocatealpha($_DST['image'], 255, 255, 255, 25);
			imagefilledrectangle($_DST['image'], 0, $_DST['height']-9, $_DST['width'], $_DST['height'], $trans);
			}

		$magnifier = imagecreatefromstring(gzuncompress(base64_decode("eJzrDPBz5+WS4mJgYOD19HAJAtLcIMzBBiRXrilXA1IsxU6eIRxAUMOR0gHkcxZ4RBYD1QiBMOOlu3V/gIISJa4RJc5FqYklmfl5CiGZuakMBoZ6hkZ6RgYGJs77ex2BalRBaoLz00rKE4tSGXwTk4vyc1NTMhMV3DKLUsvzi7KLFXwjFEAa2svWnGdgYPTydHEMqZhTOsE++1CAyNHzm2NZjgau+dAmXlAwoatQmOld3t/NPxlLMvY7sovPzXHf7re05BPzjpQTMkZTPjm1HlHkv6clYWK43Zt16rcDjdZ/3j2cd7qD4/HHH3GaprFrw0QZDHicORXl2JsPsveVTDz//L3N+WpxJ5Hff+10Tjdd2/Vi17vea79Om5w9zzyne9GLnWGrN8atby/ayXPOsu2w4quvVtxNCVVz5nAf3nDpZckBCedpqSc28WTOWnT7rZNXZSlPvFybie9EFc6y3bIMCn3JAoJ+kyyfn9qWq+LZ9Las26Jv482cDRE6Ci0B6gVbo2oj9KabzD8vyMK4ZMqMs2kSvW4chz88SXNzmeGjtj1QZK9M3HHL8L7HITX3t19//VVY8CYDg9Kvy2vDXu+6mGGxNOiltMPsjn/t9eJr0ja/FOdi5TyQ9Lz3fOqstOr99/dnro2vZ1jy76D/vYivPsBoYPB09XNZ55TQBAAJjs5s</body>")));
		imagealphablending($_DST['image'], true);
		imagecopy($_DST['image'], $magnifier, $_DST['width']-15, $_DST['height']-14, 0, 0, 11, 11);
		imagedestroy($magnifier);
		}

	// Berechnungszeit hinzufügen
	if ($params['dev'])
		{
		// Zeit anhalten
		$time['end'] = getmicrotime();
		$time = round($time['end'] - $time['start'],2);
		
		// Farben definieren
		$white_trans = imagecolorallocatealpha($_DST['image'], 255, 255, 255, 25);
		$black = ImageColorAllocate ($_DST['image'], 0, 0, 0);

		// Weißer Balken oben
		imagefilledrectangle($_DST['image'], 0, 0, $_DST['width'], 10, $white_trans);

		// Schrift mit Zeitangabe
		imagestring($_DST['image'], 1, 5, 2, 'processing time: '.$time.'s', $black);
		}
	
	if (!is_writeable($_CONFIG['cache']))
	{
		return "<div class='warning'>thumb: unable to write cache to '".$_DST['file']."'  Provide a cache parameter to use an alternate location.</div>";	
	}
	
	// Thumbnail abspeichern
	if ($_DST['type'] == 1)
		{
		imagetruecolortopalette($_DST['image'], false, 256);
		imagegif($_DST['image'], $_DST['file']);
		}
	if ($_DST['type'] == 2)
		{
		if (empty($params['quality'])) $params['quality'] = 80;
		imagejpeg($_DST['image'], $_DST['file'],$params['quality']);
		}
	if ($_DST['type'] == 3)
		{
		imagepng($_DST['image'], $_DST['file']);
		}
	
	imagedestroy($_DST['image']);
	imagedestroy($_SRC['image']);

	// Und Bild ausgeben
	return $returner;
	
	}


?>
