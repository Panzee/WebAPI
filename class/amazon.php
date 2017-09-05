<?php

class AmazonAPI {

	private $associate_tag;
	private $access_key_id;
	private $secret_access_key;
	private $baseurl = 'http://ecs.amazonaws.jp/onca/xml';

	public function __construct( $associate_tag, $access_key_id, $secret_access_key ) {
		$this->associate_tag = $associate_tag;
		$this->access_key_id = $access_key_id;
		$this->secret_access_key = $secret_access_key;
	}

	/**
	*	set country url
	*
	* @param $params string
	*/
	public function setCountry( $url ) {
		$this->baseurl = $url;
	}

	public function getRequestURL( $params ) {

		$base_params = [ "AWSAccessKeyId" => $this->access_key_id, "AssociateTag" => $this->associate_tag, "Timestamp" => gmdate( "Y-m-d\TH:i:s\Z" ) ];
		$params = array_merge( $base_params, $params );
		ksort( $params );

		$request_params = "";
		foreach ( $params as $k => $v ) {
			$request_params .= "&" . $k . "=" . rawurlencode( $v );
			$params[$k] = rawurlencode( $v );
		}
		$request_params = substr( $request_params, 1 );

		$parsed_url = parse_url( $this->baseurl );
		$signature = base64_encode( hash_hmac( "sha256", "GET\n" . $parsed_url["host"] . "\n" . $parsed_url["path"] . "\n" . $request_params, $this->secret_access_key, true ) );
		$signature = rawurlencode( $signature );

		$request_params .= "&Signature=" . $signature;

		return $this->baseurl . "?" . $request_params;
	}

}