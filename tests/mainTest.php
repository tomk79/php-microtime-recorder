<?php
/**
 * test for tomk79\microtime-recorder
 */
require_once( __DIR__.'/../php/microtime-recorder.php' );

class mainTest extends PHPUnit_Framework_TestCase{

	public function setup(){
		mb_internal_encoding('UTF-8');
	}

	/**
	 * microtime recording test
	 */
	public function testRecordeMicrotime(){
		$this->mr = new tomk79\microtimeRecorder(false);
	}

}
