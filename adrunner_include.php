<?php
/**
*AdrunnerWidgetFunctions Class
*
*@Adrunner Widget
*@author Curran Xu
*@copyright 2012-2014
*@since 1.0.1
*/
class AdrunnerWidgetFunctions extends WP_Widget {

	public $info = array();
//	public $base_url = "http://adrunner.pricerunner.se/feedwidget";
	public $base_url = "http://192.168.102.118:8080/makeURL/servlet/urlservlet";

	function AdrunnerWidgetFunctions( ){
	}

	function get_adrunner_content($slider){
		$tplId = $slider ? 53 : 60;
		$this->get_page_info($tplId);
		$url = $this->combine_url();
		$content = $this->curl_content($url);
        return $content;
	}
	function curl_content($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		if ($info['http_code'] == '200'){
			return $content;
		} else {
			return "";
		}
	}
	function combine_url(){
		$url = $this->base_url;
		$info = $this->info;
		$info = array_map("urlencode", $info);

		$uri = "";
		foreach ($info as $key => $value){
			if ($value){
				$uri .= $uri ? "&{$key}={$value}" : "{$key}={$value}";
			}
		}

		if ($uri){
			$url .= "?{$uri}";
		}
		return $url;
	}

	function get_page_info($tplId){

		$info = array();
		$info['domain'] = $_SERVER["HTTP_HOST"];
		$info['url'] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		if (is_home() || is_front_page()){//is home page
			$info['title'] = "homepage";
		} else if (is_single()){//is post page
			$info['title'] = wp_title("", false);
			$info['url'] = get_permalink();
			$info['tags'] = get_the_tags();
			$info['categories'] = get_the_category();
		} else if (is_page()){//is page
			$info['title'] = wp_title("", false);
		} else if (is_category()){//is category page
			$info['title'] = wp_title("", false);
			$info['categories'] = single_cat_title("", false);
		} else {
			$info['title'] = "Advertisement";
		}

		//has tags
		if (!is_single() && has_tag()){
			$info['tags'] = get_the_tags();
		}

		if ($info['categories'] && is_array($info['categories'])){
			$implode_categories = "";
			foreach ($info['categories'] as $category){
				$implode_categories .= $implode_categories ? "," . $category->name : $category->name;
			}
			if ($implode_categories){
				$info['categories'] = $implode_categories;
			}
		}

		if ($info['tags'] && is_array($info['tags'])){
			$implode_tags = "";
			foreach ($info['tags'] as $tag){
				$implode_tags .= $implode_tags ? "," . $tag->name : $tag->name;
			}
			if ($implode_tags){
				$info['tags'] = $implode_tags;
			}
		}
		$info['template'] = $tplId;
		$this->info = $info;
	}

}