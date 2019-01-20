<?php
/**
 * test for tomk79\microtime-recorder
 */
require_once( __DIR__.'/../php/microtime-recorder.php' );

class clearTest extends PHPUnit_Framework_TestCase{

	public function setup(){
		mb_internal_encoding('UTF-8');
	}

	/**
	 * clear test data
	 */
	public function testClear(){
		@unlink(__DIR__.'/dist/record.txt');
		@unlink(__DIR__.'/dist/record.csv');
		@unlink(__DIR__.'/dist/record.tsv');
	}

}
