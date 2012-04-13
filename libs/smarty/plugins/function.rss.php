<?php
	/**
	 * smarty template function
	 * name: rss fetch resource
	 * package: InSpire CMS
	 * author: Kulikov Alexey <alex [at] inses [dot] ru
	 * 
	 * params:
	 *   mandatory: 	'source' rss source (string) 
	 *   optional:		'items' items to fetch (int)				default: 10
	 * 					'var' smarty variable name (string) 		default: rss
	 * 					'cache' whether to cache the result	(bool)	default: true
	 * 					'cachelt' lifetime of cache in seconds (int)default: 86400 seconds or one day
	 * @version 1.2
	 * @copyright 2004, InSES Corporation and Alexey Kulikov
	 * 
	 * @note 	make sure you have a template named empty.txt that contains nothing
	 * 			but {$someData} in it. This template will be used to cache RSS feeds
	 * 			locally. Also note that caching can be disabled, but this makes
	 * 			syndication with slow servers rather inefficient
	 **/
	 
 	function smarty_function_rss($params, &$smarty){
		//error check
		if(empty($params['source'])) {
			$smarty->trigger_error("rss: missing 'source' parameter");
        	return;
		}
		
		//check for optional variables
		//smarty variable name
		if(!empty($params['var'])){
			$variableName = (string)$params['var'];
		}else{
			$variableName = "rss";
		}
		
		//items to fetch
		if(!empty($params['items'])){
			$itemsToGet = (int)$params['items'];
		}else{
			$itemsToGet = 10;
		}
		
		//cache feed?
		if(!empty($params['cache'])){
			if($params['cache'] == 'false'){
				$cache = false;
			}else{
				$cache = true;
			}
		}else{
			$cache = true;
		}
		
		//cache lifetime
		if(!empty($params['cachelt'])){
			$lifeTime = (int)$params['cachelt'];
		}else{
			$lifeTime = 86400;
		}
		
		## GET RSS CONTENT ###################################
		#
		
		//set cache status
		//$oldCacheStatus = $smarty->caching;
		//$oldCacheLifetime = $smarty->cache_lifetime;
		//$smarty->caching = $cache;
		//$smarty->cache_lifetime = $lifeTime;
		//$cacheID = "rss|".md5($params['source']);
		
		//was the feed cached?
		//if(!$smarty->is_cached('empty.txt',$cacheID)){ //no
			//set ini setting to allow to read remote files
			//if(@ini_get("allow_url_fopen") == 0) {
			//	@ini_set("allow_url_fopen", 1);
			//}
		
			//fetch file
			if($file = fopen($params['source'],"r")){
				$content = '';
				while (!feof($file)) {
  					$content .= fread($file, 8192);
				}
				fclose($file);
			//}else{
			//	return;
			}
			
			//$smarty->assign("someData",$content);
		//}
		
		//get content from smarty
		//$content = $smarty->fetch('empty.txt',$cacheID);
		
		//reset cache status
		//$smarty->caching = $oldCacheStatus;
		//$smarty->cache_lifetime = $oldCacheLifetime;
		
		#
		## /END GET RSS CONTENT ##############################
		
		//process content
		$items = explode("<item", $content);
		unset($items[0]); //drop first reference
		
		$data = array();
		$tempArray = array();
		$counter = 0;
		
		//print "<hr>";
		//print htmlentities( print_r($items,1) );
		//print "<hr>";
		
		$search = array("<![CDATA[","]]>");
		$replace = array("","");
		
		foreach($items as $item){
			$tempArray['title'] = untag($item,"title");
			$tempArray['description'] = str_replace($search,$replace, untag($item,"description") );
			$tempArray['link'] = untag($item,"link");
			$data[] = $tempArray;
			
			//increment counter
			$counter++;
			if($counter >= $itemsToGet){
				break;
			}
		}
		
		//give data to smarty and terminate
		$smarty->assign($variableName,$data);		
		return;
	}
	
	
	/**
	 * untag() 	extracts the content of all tags ($tag) in string ($string). 
	 * 			When ($mode) is 1 it returns the content as an array, otherwise 
	 * 			as a string
	 * 
	 * @param $string
	 * @param $tag
	 * @return 
	 **/
	function untag ($string, $tag){
		$z = strpos ($string, '<'.$tag.'>');
		if ($z!==false) {
			$z=$z+ strlen ($tag) + 2;
			$z2 = strpos ($string, '</'.$tag.'>');
			$s = substr ($string, $z, $z2 - $z);
			return $s;
		}
	}
?>