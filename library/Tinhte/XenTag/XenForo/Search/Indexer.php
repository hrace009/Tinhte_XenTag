<?php

class Tinhte_XenTag_XenForo_Search_Indexer extends XenForo_Search_Indexer
{
    protected $_otherIndexer = null;
    protected $_extraMetadata = array();

    /** @noinspection PhpMissingParentConstructorInspection
     * @param XenForo_Search_Indexer $otherIndexer
     * @param array $extraMetadata
     */
    public function __construct(XenForo_Search_Indexer $otherIndexer, array $extraMetadata)
    {
        $this->_sourceHandler = $otherIndexer->_sourceHandler;
        $this->_otherIndexer = $otherIndexer;
        $this->_extraMetadata = $extraMetadata;
    }

    public function insertIntoIndex($contentType, $contentId, $title, $message, $itemDate, $userId, $discussionId = 0, array $metadata = array())
    {
        $metadata = XenForo_Application::mapMerge($metadata, $this->_extraMetadata);
        $this->_otherIndexer->insertIntoIndex($contentType, $contentId, $title, $message, $itemDate, $userId, $discussionId, $metadata);
    }

}
