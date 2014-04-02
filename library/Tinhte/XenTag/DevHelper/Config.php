<?php
class Tinhte_XenTag_DevHelper_Config extends DevHelper_Config_Base
{
	protected $_dataClasses = array(
		'tag' => array(
			'name' => 'tag',
			'camelCase' => 'Tag',
			'camelCaseWSpace' => 'Tag',
			'fields' => array(
				'tag_id' => array('name' => 'tag_id', 'type' => 'uint', 'autoIncrement' => true),
				'tag_text' => array('name' => 'tag_text', 'type' => 'string', 'length' => 100, 'required' => true),
				'tag_title' => array('name' => 'tag_title', 'type' => 'string'),
				'tag_description' => array('name' => 'tag_description', 'type' => 'string'),
				'created_date' => array('name' => 'created_date', 'type' => 'uint', 'required' => true),
				'created_user_id' => array('name' => 'created_user_id', 'type' => 'uint', 'required' => true),
				'content_count' => array('name' => 'content_count', 'type' => 'uint', 'default' => 0),
				'target_type' => array('name' => 'target_type', 'type' => 'string', 'length' => 25, 'required' => true, 'default' => ''),
				'target_id' => array('name' => 'target_id', 'type' => 'uint', 'required' => true, 'default' => 0),
				'target_data' => array('name' => 'target_data', 'type' => 'serialized'),
				'is_staff' => array('name' => 'is_staff', 'type' => 'uint', 'required' => true, 'default' => 0),
				'latest_tagged_contents' => array('name' => 'latest_tagged_contents', 'type' => 'serialized'),
				'tag_options' => array('name' => 'tag_options', 'type' => 'serialized'),
			),
			'id_field' => 'tag_id',
			'title_field' => 'tag_text',
			'primaryKey' => array('tag_id'),
			'indeces' => array(
				'tag_text' => array('name' => 'tag_text', 'fields' => array('tag_text'), 'type' => 'NORMAL'),
			),
			'files' => array(
				'data_writer' => array('className' => 'Tinhte_XenTag_DataWriter_Tag', 'hash' => '11f8794a072b6835cc9ff97fb7bed26c'),
				'model' => array('className' => 'Tinhte_XenTag_Model_Tag', 'hash' => '32bd7ab934ea65f9c5de7fb30924cf66'),
				'route_prefix_admin' => array('className' => 'Tinhte_XenTag_Route_PrefixAdmin_Tag', 'hash' => '7ab5c56e7953bbd649efc61ce482df8c'),
				'controller_admin' => array('className' => 'Tinhte_XenTag_ControllerAdmin_Tag', 'hash' => 'f71f66e6acb03414447869fc68d8a7d1'),
			),
		),
		'tagged_content' => array(
			'name' => 'tagged_content',
			'camelCase' => 'TaggedContent',
			'camelCaseWSpace' => 'Tagged Content',
			'fields' => array(
				'tag_id' => array('name' => 'tag_id', 'type' => 'uint', 'required' => true),
				'content_type' => array('name' => 'content_type', 'type' => 'string', 'length' => 25, 'required' => true),
				'content_id' => array('name' => 'content_id', 'type' => 'uint', 'required' => true),
				'tagged_user_id' => array('name' => 'tagged_user_id', 'type' => 'uint', 'required' => true),
				'tagged_date' => array('name' => 'tagged_date', 'type' => 'uint', 'required' => true),
			),
			'id_field' => 'n/a',
			'title_field' => 'n/a',
			'primaryKey' => array('tag_id', 'content_type', 'content_id'),
			'indeces' => array(),
			'files' => array(
				'data_writer' => array('className' => 'Tinhte_XenTag_DataWriter_TaggedContent', 'hash' => 'bf2911262bbea4712f343005b2456750'),
				'model' => array('className' => 'Tinhte_XenTag_Model_TaggedContent', 'hash' => '1c1dfcbdec9d51ee70b2b76069fb5cee'),
				'route_prefix_admin' => false,
				'controller_admin' => false,
			),
		),
		'tag_watch' => array(
			'name' => 'tag_watch',
			'camelCase' => 'TagWatch',
			'camelCaseWSpace' => 'Tag Watch',
			'fields' => array(
				'user_id' => array('name' => 'user_id', 'type' => 'uint', 'required' => true),
				'tag_id' => array('name' => 'tag_id', 'type' => 'uint', 'required' => true),
				'send_alert' => array('name' => 'send_alert', 'type' => 'uint', 'required' => true, 'default' => 0),
				'send_email' => array('name' => 'send_email', 'type' => 'uint', 'required' => true, 'default' => 0),
			),
			'id_field' => 'n/a',
			'title_field' => 'n/a',
			'primaryKey' => array('tag_id', 'user_id'),
			'indeces' => array(
				'user_id' => array('name' => 'user_id', 'fields' => array('user_id'), 'type' => 'NORMAL'),
			),
			'files' => array(
				'data_writer' => false,
				'model' => false,
				'route_prefix_admin' => false,
				'controller_admin' => false,
			),
		),
	);
	protected $_dataPatches = array(
		'xf_thread' => array(
			'tinhte_xentag_tags' => array('name' => 'tinhte_xentag_tags', 'type' => 'serialized'),
			'tinhte_xentag_is_tagged' => array('name' => 'tinhte_xentag_is_tagged', 'type' => 'uint', 'default' => 0),
		),
		'xf_tinhte_xentag_tag' => array(
			'latest_tagged_contents' => array('name' => 'latest_tagged_contents', 'type' => 'serialized'),
			'tag_description' => array('name' => 'tag_description', 'type' => 'string'),
			'target_type' => array('name' => 'target_type', 'type' => 'string', 'length' => 25, 'required' => true, 'default' => ''),
			'target_id' => array('name' => 'target_id', 'type' => 'uint', 'required' => true, 'default' => 0),
			'target_data' => array('name' => 'target_data', 'type' => 'serialized'),
			'is_staff' => array('name' => 'is_staff', 'type' => 'uint', 'required' => true, 'default' => 0),
			'tag_title' => array('name' => 'tag_title', 'type' => 'string'),
			'tag_options' => array('name' => 'tag_options', 'type' => 'serialized'),
		),
		'xf_forum' => array(
			'tinhte_xentag_options' => array('name' => 'tinhte_xentag_options', 'type' => 'serialized'),
			'tinhte_xentag_tags' => array('name' => 'tinhte_xentag_tags', 'type' => 'serialized'),
		),
		'xf_page' => array(
			'tinhte_xentag_tags' => array('name' => 'tinhte_xentag_tags', 'type' => 'serialized'),
		),
		'xf_resource' => array(
			'tinhte_xentag_tags' => array('name' => 'tinhte_xentag_tags', 'type' => 'serialized'),
		),
		'xf_search' => array(
			'tinhte_xentag_tags' => array('name' => 'tinhte_xentag_tags', 'type' => 'serialized'),
		),
	);
	protected $_exportPath = '/Users/sondh/XenForo/Tinhte/XenTag';
	protected $_exportIncludes = array();

	/**
	 * Return false to trigger the upgrade!
	 * common use methods:
	 * 	public function addDataClass($name, $fields = array(), $primaryKey = false, $indeces = array())
	 *	public function addDataPatch($table, array $field)
	 *	public function setExportPath($path)
	**/
	protected function _upgrade()
	{
		return true; // remove this line to trigger update

		/*
		$this->addDataClass(
				'name_here',
				array( // fields
						'field_here' => array(
								'type' => 'type_here',
								// 'length' => 'length_here',
								// 'required' => true,
								// 'allowedValues' => array('value_1', 'value_2'),
								// 'default' => 0,
								// 'autoIncrement' => true,
						),
						// other fields go here
				),
				'primary_key_field_here',
				array( // indeces
						array(
								'fields' => array('field_1', 'field_2'),
								'type' => 'NORMAL', // UNIQUE or FULLTEXT
						),
				),
		);
		*/
	}
}