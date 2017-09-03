<?php

class DmmAPI {

	private $api_id;
	private $affiliate_id;

	public function __construct( $api_id, $affiliate_id ) {
		$this->api_id = $api_id;
		$this->affiliate_id = $affiliate_id;
	}


	// $params_itemlist [ 'site' => 'DMM.com', 'service' => '', 'floor' => '', 'hits' => '20', 'offset' => '1', '' => '', 'sort' => 'rank', '	keyword' => '', 'cid' => '', 'article' => '', 'article_id' => '', 'gte_date' => '', 'lte_date' => '', 'mono_stock' => '', 'output' => 'xml', 'callback' => '' ];

	/**
	*	create requestURL
	*
	* @param $params array https://affiliate.dmm.com/api/v3/itemlist.html
	* @return string
	*/
	public function createRequestURL( $params = [] ) {

		$params_itemlist = [ 'api_id' => $this->api_id, 'affiliate_id' => $this->affiliate_id ,'site' => 'DMM.com', 'output' => 'xml' ];
		$params = array_merge( $params_itemlist, $params );

		$url = 'https://api.dmm.com/affiliate/v3/ItemList?';
		foreach( $params as $k => $v ) {
			$url .= $k . '=' . $v . '&';
		}
		return substr( $url, 0, -1 );
	}

}