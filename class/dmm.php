<?php

/* https://affiliate.dmm.com/api/v3/itemlist.html */
class DmmAPI {

	private $api_id;
	private $affiliate_id;
	private $url;
	private $xml;

	public function __construct( $api_id, $affiliate_id ) {
		$this->api_id = $api_id;
		$this->affiliate_id = $affiliate_id;
	}

	/**
	*	create requestURL
	*
	* @param $params array https://affiliate.dmm.com/api/v3/itemlist.html
	* @return string
	*/
	public function getRequestURL( $params = [] ) {
		$base_params = [ 'api_id' => $this->api_id, 'affiliate_id' => $this->affiliate_id ,'site' => 'DMM.com', 'output' => 'xml' ];
		$params = array_merge( $base_params, $params );
		$url = 'https://api.dmm.com/affiliate/v3/ItemList?';
		foreach( $params as $k => $v ) {
			$url .= $k . '=' . $v . '&';
		}
		$this->url = substr( $url, 0, -1 );
		return $this->url;
	}

	/**
	*	get response XML type
	*
	* @param $url string
	* @return string
	*/
	public function getResponseXML( $url ) {
		$this->xml = simplexml_load_string( file_get_contents( $url ) );
		return $this->xml;
	}

	/**
	*	get response XML type
	*
	* @param $xml object
	* @return array
	*/
	public function getResponseAll( $xml ) {
		$items = [];
		foreach( $xml->result->items->item as $item ) {
			$items[] = [ 'title' => $item->title,
									 'URL' => $item->URL,
									 'affiliateURL' => $item->affiliateURL,
									 'imageURL'=> $item->imageURL->large,
									 'sampleMovieURL'=> $item->sampleMovieURL->size_720_480,
									 'date'=> $item->date,
								 ];
		}
		return $items;
	}
}