<?php

class Tinhte_XenTag_Helper {
	public static function unserialize($string) {
		$array = $string;
		
		if (!is_array($array)) $array = @unserialize($array);
		
		if (empty($array)) $array = array();
		
		return $array;
	}
	
	public static function utf8_strrpos($haystack, $needle, $offset) {
		if (UTF8_MBSTRING) {
			return mb_strrpos($haystack, $needle, $offset);
		} else {
			return strrpos($haystack, $needle, $offset);
		}
	}
	
	public static function utf8_stripos($haystack, $needle, $offset) {
		if (UTF8_MBSTRING) {
			return mb_stripos($haystack, $needle, $offset);
		} else {
			return stripos($haystack, $needle, $offset);
		}
	}
	
	public static function utf8_strripos($haystack, $needle, $offset) {
		if (UTF8_MBSTRING) {
			return mb_strripos($haystack, $needle, $offset);
		} else {
			return strripos($haystack, $needle, $offset);
		}
	}
	
	public static function explodeTags($tagsStr) {
		// sondh@2012-08-12
		// try to use mb_split if possible to avoid splitting the wrong separator in UTF8 strings
		if (function_exists('mb_split')) {
			return mb_split(Tinhte_XenTag_Constants::REGEX_SEPARATOR, $tagsStr);
		} else {
			return preg_split('/' . Tinhte_XenTag_Constants::REGEX_SEPARATOR . '/', $tagsStr, -1, PREG_SPLIT_NO_EMPTY);
		}
	}
	
	public static function isTagContainingSeparator($tagText) {
		// sondh@2012-08-12
		// we have to add the u modifier to have the regular expression interpreted as unicode
		// it's 2012 and PHP still doesn't handle unicode transparently... *sigh*
		return preg_match('/' . Tinhte_XenTag_Constants::REGEX_SEPARATOR . '/u', $tagText) == 1;
	}
	
	public static function getImplodedTagsFromThread($thread, $getLinks = false) {
		if (is_array($thread) AND isset($thread[Tinhte_XenTag_Constants::FIELD_THREAD_TAGS])) {
			$tags = self::unserialize($thread[Tinhte_XenTag_Constants::FIELD_THREAD_TAGS]);
		} else {
			$tags = array();
		}
		
		return self::_getImplodedTags($tags, $getLinks);
	}
	
	public static function getImplodedTagsFromPage($page, $getLinks = false) {
		if (is_array($page) AND isset($page[Tinhte_XenTag_Constants::FIELD_PAGE_TAGS])) {
			$tags = self::unserialize($page[Tinhte_XenTag_Constants::FIELD_PAGE_TAGS]);
		} else {
			$tags = array();
		}
		
		return self::_getImplodedTags($tags, $getLinks);
	}
	
	public static function getImplodedTagsFromForum($forum, $getLinks = false) {
		if (is_array($forum) AND isset($forum[Tinhte_XenTag_Constants::FIELD_FORUM_TAGS])) {
			$tags = self::unserialize($forum[Tinhte_XenTag_Constants::FIELD_FORUM_TAGS]);
		} else {
			$tags = array();
		}
		
		return self::_getImplodedTags($tags, $getLinks);
	}
	
	protected static function _getImplodedTags(array $tags, $getLinks = false) {
		$result = array();
		
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
		$tagText = utf8_trim($tagText);
		$tagText = utf8_strtolower($tagText);
		
		return $tagText;
	}
}