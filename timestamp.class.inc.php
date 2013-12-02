<?php
	/*
	 * @author Antoine De Gieter
	 *
	 * Class Timestamp: modelises an unix timestamp
	 * Can be formatted as a date.
	 *
	 */
	class Timestamp {
		private $ts;

		public function __construct( $ts ) {
			$this->ts = $ts;
		}

		public function format( $format ) {
			return date( $format, $this->ts )
		}

		public function year() {
			return date( 'Y', $this->ts );
		}

		public function month() {
			return date( 'm', $this->ts );
		}

		public function day() {
			return date( 'd', $this->ts );
		}

		public function hour() {
			return date( 'h', $this->ts );
		}

		public function minute() {
			return date( 'i', $this->ts );
		}

		public function second() {
			return date( 's', $this->ts );
		}

		/* ===
		 * STATIC METHODS
		 * ===
		 */

		public static function current() {
			return time();
		}

		public static function difference( $ts1, $ts2, $ordered = false ) {
			$ordered
			&& return $ts2 - $ts1;
			return max( $ts1, $ts2 ) - min( $ts1, $ts2 );
		}