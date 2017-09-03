<?php

/* https://developers.google.com/analytics/devguides/reporting/core/v3/?hl=ja */
class AnalyticsAPI {

	private $view_id;
	private $key_file_location;
	private $analytics;

	public function __construct( $view_id, $key_file_location, $autoload_location ) {
		require_once $autoload_location;
		$this->view_id = $view_id;
		$this->key_file_location = $key_file_location;
		$this->analytics = $this->initializeAnalytics();
	}

	/**
	*	init
	*
	* @return object
	*/
	private function initializeAnalytics() {

		$client = new Google_Client();
		$client->setApplicationName( "Hello Analytics Reporting" );
		$client->setAuthConfig( $this->key_file_location );
		$client->setScopes( ['https://www.googleapis.com/auth/analytics.readonly'] );

		return new Google_Service_AnalyticsReporting( $client );
	}

	public function getReport() {

		/* Create the DateRange object. */
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();

		$dateRange->setStartDate( "30daysAgo" );
		$dateRange->setEndDate( "yesterday" );

		$sessions = new Google_Service_AnalyticsReporting_Metric();
		$sessions->setExpression( "ga:sessions" );
		$sessions->setAlias( "sessions" );

		$dimention = new Google_Service_AnalyticsReporting_Dimension();
		$dimention->setName( 'ga:landingPagePath' );

		$orderby = new Google_Service_AnalyticsReporting_OrderBy();
		$orderby->setFieldName( "ga:sessions" );
		$orderby->setOrderType( "VALUE" );
		$orderby->setSortOrder( "DESCENDING" );

		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId( $this->view_id );
		$request->setDateRanges( $dateRange );
		$request->setMetrics( array( $sessions ) );
		$request->setDimensions( array( $dimention ) );
		$request->setOrderBys( $orderby );

		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests( array( $request) );

		return $this->analytics->reports->batchGet( $body );
	}

	public function getResults( $reports ) {

		$results = [];

		$report = $reports[0];
		$rows = $report->getData()->getRows();

		for ( $rowIndex = 0; $rowIndex < count( $rows ); $rowIndex++ ) {

			$row = $rows[ $rowIndex ];
			$dimensions = $row->getDimensions();
			$metrics = $row->getMetrics();
			$session = $metrics[0]->getValues()[0];

			$url = $dimensions[0];
			$volume = $session;

			$results[] = [ 'url' => $url, 'volume' => $volume ];
		}

		return $results;
	}

}