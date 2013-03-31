<?php

class Tinhte_XenTag_Helper {
	public static function unserialize($string) {
		$array = $string;
		
		if (!is_array($array)) $array = @unserialize($array);
		
		if (empty($array)) $array = array();
		
		return $array;
	}
	
	public static function explodeTags($tagsStr) {
		// sondh@2012-08-12
		// try to use mb_split if possible to avoid splitting the wrong separator in UTF8 strings
		if (function_exists('mb_split')) {
			return mb_split(Tinhte_XenTag_Constants::REGEX_SEPARATOR, $tagsStr);
		} else {
			return preg_split(Tinhte_XenTag_Constants::REGEX_SEPARATOR, $tagsStr, -1, PREG_SPLIT_NO_EMPTY);
		}
	}
	
	public static function isTagContainingSeparator($tagText) {
		// sondh@2012-08-12
		// we have to add the u modifier to have the regular expression interpreted as unicode
		// it's 2012 and PHP still doesn't handle unicode transparently... *sigh*
		return preg_match(Tinhte_XenTag_Constants::REGEX_SEPARATOR . 'u', $tagText) == 1;
	}
	
	public static function getImplodedTagsFromThread($thread, $getLinks = false) {
		$result = array();
		
		if (is_array($thread) AND isset($thread[Tinhte_XenTag_Constants::FIELD_THREAD_TAGS])) {
			$tags = self::unserialize($thread[Tinhte_XenTag_Constants::FIELD_THREAD_TAGS]);
		} else {
			$tags = array();
		}
		
		if ($getLinks) {
			foreach ($tags as $tag) {
				$result[] = '<a href="' 
								. XenForo_Link::buildPublicLink(Tinhte_XenTag_Option::get('routePrefix'), $tag)
								. '">' . htmlspecialchars($tag) . '</a>';
			}
		} else {
			foreach ($tags as $tag) {
				$result[] = htmlspecialchars($tag);
			}
		}
		
		return implode(', ', $result);
	}
	
	public static function getOption($key) {
		return Tinhte_XenTag_Option::get($key);
	}
	
	public static function getSafeTagsTextArrayForSearch(array $tagsText) {
		$safe = array();
		
		foreach ($tagsText as $tagText) {
			// sondh@2012-08-23
			// changed to use md5 because search index sucks at utf8 (bug time)
			$safe[] = md5(self::getNormalizedTagText($tagText));
		}
		
		return $safe;
	}
	
	public static function getNormalizedTagText($tagText) {
		$tagText = trim($tagText);
		$tagText = strtolower($tagText);
		
		return $tagText;
	}
}