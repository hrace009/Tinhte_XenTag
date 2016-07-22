<?php

class Tinhte_XenTag_XenForo_ViewPublic_Tag_View extends XFCP_Tinhte_XenTag_XenForo_ViewPublic_Tag_View
{
    protected $_Tinhte_XenTag_buggyXmlNamespace = null;
    protected $_Tinhte_XenTag_bbCodeParsers = array();

    public function prepareParams()
    {
        parent::prepareParams();

        if (isset($this->_params[__METHOD__])) {
            return;
        }
        $this->_params[__METHOD__] = true;

        if (Tinhte_XenTag_Option::get('seoKwStuffing')
            && isset($this->_params['results']['results'])
        ) {
            $prefixCandidates = array(
                utf8_strtolower($this->_params['tag']['tag']),
            );
            if (!empty($this->_params['tag']['tinhte_xentag_title'])) {
                $prefixCandidates[] = utf8_strtolower($this->_params['tag']['tinhte_xentag_title']);
            }

            foreach ($this->_params['results']['results'] as &$resultRef) {
                if (!empty($resultRef['content']['title'])) {
                    $resultRef['content']['title'] = preg_replace_callback(
                        '#^\[(?<prefix>[^\]]+)\]\s+#i',
                        function (array $matches) use ($prefixCandidates) {
                            $prefix = $matches['prefix'];
                            $prefix = utf8_strtolower($prefix);
                            foreach ($prefixCandidates as $prefixCandidate) {
                                if (strpos($prefixCandidate, $prefix) === 0) {
                                    // partial match found, remove the prefix
                                    return '';
                                }
                            }

                            return $matches[0];
                        },
                        $resultRef['content']['title']
                    );
                }
            }
        }

        if (!empty($this->_params['tag']['tinhte_xentag_richtext'])) {
            $formatter = XenForo_BbCode_Formatter_Base::create('Base', array('view' => $this));
            $parser = new XenForo_BbCode_Parser($formatter);
            $richtextHtml = XenForo_ViewPublic_Helper_Message::getBbCodeWrapper($this->_params['tag'],
                $parser, array('messageKey' => 'tinhte_xentag_richtext'));
            $this->_params['tag']['tinhte_xentag_richtext'] = $richtextHtml;
        }
    }

    /**
     * @return string
     * @throws Zend_Exception
     * @throws Zend_Feed_Exception
     *
     * @see XenForo_ViewPublic_Forum_View::renderRss
     */
    public function renderRss()
    {
        if (is_callable(array('parent', 'renderRss'))) {
            return call_user_func(array('parent', 'renderRss'));
        }

        $tag = $this->_params['tag'];

        $feed = new Zend_Feed_Writer_Feed();
        $feed->setEncoding('utf-8');
        if (!empty($tag['tinhte_xentag_title'])) {
            $feed->setTitle($tag['tinhte_xentag_title']);
        } else {
            $feed->setTitle($tag['tag']);
        }
        if (!empty($tag['tinhte_xentag_description'])) {
            $feed->setDescription($tag['tinhte_xentag_description']);
        } else {
            $feed->setDescription(strval(new XenForo_Phrase('tinhte_xentag_all_contents_tagged_x',
                array(
                    'board_title' => XenForo_Application::get('options')->get('boardTitle'),
                    'tag_text' => $tag['tag']
                )
            )));
        }

        $feed->setLink(XenForo_Link::buildPublicLink('canonical:tags', $tag));
        if (!$this->_Tinhte_XenTag_isBuggyXmlNamespace()) {
            $feed->setFeedLink(XenForo_Link::buildPublicLink('canonical:tags.rss', $tag), 'rss');
        }
        $feed->setDateModified($tag['last_use_date']);
        $feed->setLastBuildDate(XenForo_Application::$time);
        if (XenForo_Application::get('options')->boardTitle) {
            $feed->setGenerator(XenForo_Application::get('options')->boardTitle);
        }

        foreach ($this->_params['results']['results'] AS $result) {
            $entry = $this->_Tinhte_XenTag_prepareRssEntry($result, $feed);

            if ($entry !== false) {
                $feed->addEntry($entry);
            }
        }

        return $feed->export('rss');
    }

    protected function _Tinhte_XenTag_prepareRssEntry(
        /** @noinspection PhpUnusedParameterInspection */
        $result,
        Zend_Feed_Writer_Feed $feed
    ) {
        $entry = false;

        if ($result[XenForo_Model_Search::CONTENT_TYPE] == 'thread') {
            $thread = $result['content'];

            $entry = $feed->createEntry();
            $entry->setTitle($thread['title']);
            $entry->setLink(XenForo_Link::buildPublicLink('canonical:threads', $thread));
            $entry->setDateCreated(new Zend_Date($thread['post_date'], Zend_Date::TIMESTAMP));
            $entry->setDateModified(new Zend_Date($thread['last_post_date'], Zend_Date::TIMESTAMP));

            $discussionRssContentLength = XenForo_Application::getOptions()->get('discussionRssContentLength');

            if (!empty($thread['message'])
                && $discussionRssContentLength > 0
            ) {
                $bbCodeParser = $this->_Tinhte_XenTag_getBbCodeParser('Base');
                $bbCodeSnippetParser = $this->_Tinhte_XenTag_getBbCodeParser('XenForo_BbCode_Formatter_BbCode_Clean');
                $rendererStates = array(
                    'disableProxying' => true
                );

                $wordTrimmed = XenForo_Helper_String::wholeWordTrim($thread['message'], $discussionRssContentLength);
                $snippet = $bbCodeSnippetParser->render($wordTrimmed, $rendererStates);
                if ($snippet != $thread['message']) {
                    $snippet .= "\n\n[url='" . XenForo_Link::buildPublicLink('canonical:threads',
                            $thread) . "']" . $thread['title'] . '[/url]';
                }

                $content = trim($bbCodeParser->render($snippet, $rendererStates));
                if (strlen($content)) {
                    $entry->setContent($content);
                }
            }

            if (!$this->_Tinhte_XenTag_isBuggyXmlNamespace()) {
                $entry->addAuthor(array(
                    'name' => $thread['username'],
                    'uri' => XenForo_Link::buildPublicLink('canonical:members', $thread)
                ));
                if ($thread['reply_count']) {
                    $entry->setCommentCount($thread['reply_count']);
                }
            }
        }

        return $entry;
    }

    protected function _Tinhte_XenTag_isBuggyXmlNamespace()
    {
        if ($this->_Tinhte_XenTag_buggyXmlNamespace === null) {
            $this->_Tinhte_XenTag_buggyXmlNamespace = (defined('LIBXML_DOTTED_VERSION') && LIBXML_DOTTED_VERSION == '2.6.24');
        }

        return $this->_Tinhte_XenTag_buggyXmlNamespace;
    }

    /**
     * @param string $formatterClass
     * @return XenForo_BbCode_Parser
     */
    protected function _Tinhte_XenTag_getBbCodeParser($formatterClass)
    {
        if (!isset($this->_Tinhte_XenTag_bbCodeParsers[$formatterClass])) {
            $bbCodeFormatter = XenForo_BbCode_Formatter_Base::create($formatterClass, false);
            $this->_Tinhte_XenTag_bbCodeParsers[$formatterClass] = XenForo_BbCode_Parser::create($bbCodeFormatter);
        }

        return $this->_Tinhte_XenTag_bbCodeParsers[$formatterClass];
    }
}