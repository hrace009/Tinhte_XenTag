<?php

class Tinhte_XenTag_Search_DataHandler_Page extends XenForo_Search_DataHandler_Abstract {
	
	protected $_pageModel = null;

	protected function _insertIntoIndex(XenForo_Search_Indexer $indexer, array $data, array $parentData = null) {
		$metadata = array();
		
		$tags = Tinhte_XenTag_Helper::unserialize($data[Tinhte_XenTag_Constants::FIELD_PAGE_TAGS]);
		$metadata[Tinhte_XenTag_Constants::SEARCH_METADATA_TAGS] = Tinhte_XenTag_Helper::getSafeTagsTextArrayForSearch($tags);
		
		$indexer->insertIntoIndex(
			'tinhte_xentag_page', $data['node_id'],
			$data['title'], $data['description'],
			$data['publish_date'], XenForo_Visitor::getUserId(), 0, $metadata
		);
	}

	protected function _updateIndex(XenForo_Search_Indexer $indexer, array $data, array $fieldUpdates) {
		$indexer->updateIndex('tinhte_xentag_page', $data['node_id'], $fieldUpdates);
	}

	protected function _deleteFromIndex(XenForo_Search_Indexer $indexer, array $dataList) {
		$ids = array();
		
		foreach ($dataList AS $data) {
			$ids[] = $data['node_id'];
		}

		$indexer->deleteFromIndex('tinhte_xentag_page', $ids);
	}

	public function rebuildIndex(XenForo_Search_Indexer $indexer, $lastId, $batchSize) {
		$ids = $this->_getPageModel()->Tinhte_XenTag_getPageIdsInRange($lastId, $batchSize);
		
		if (!$ids) {
			return false;
		}

		$this->quickIndex($indexer, $ids);

		return max($ids);
	}

	public function quickIndex(XenForo_Search_Indexer $indexer, array $contentIds) {
		$pages = $this->_getPageModel()->Tinhte_XenTag_getPagesByIds($contentIds);

		foreach ($pages AS $page) {
			$this->insertIntoIndex($indexer, $page);
		}

		return true;
	}

	public function getDataForResults(array $ids, array $viewingUser, array $resultsGrouped) {
		$pages = $this->_getPageModel()->Tinhte_XenTag_getPagesByIds($ids);

		return $pages;
	}

	public function canViewResult(array $result, array $viewingUser) {
		return true;
	}

	public function getResultDate(array $result) {
		return $result['publish_date'];
	}

	/**
	 * Renders a result to HTML.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::renderResult()
	 */
	public function renderResult(XenForo_View $view, array $result, array $search) {
		return $view->createTemplateObject('tinhte_xentag_search_result_page', array(
			'page' => $result,
			'search' => $search
		));
	}

	public function getSearchContentTypes() {
		return array('tinhte_xentag_page');
	}
	
	/**
	 * @return XenForo_Model_Page
	 */
	protected function _getPageModel() {
		if (!$this->_pageModel) {
			$this->_pageModel = XenForo_Model::create('XenForo_Model_Page');
		}

		return $this->_pageModel;
	}
}