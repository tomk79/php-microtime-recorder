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
	 * microtime recording as Text test
	 */
	public function testRecordeMicrotimeAsTxt(){
		@unlink(__DIR__.'/dist/record.txt');
		ob_start();
		$mr = new tomk79\microtimeRecorder(__DIR__.'/dist/record.txt');
		$this->assertTrue( is_object($mr) );

		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_array($record) );
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_null($record['elapsed']) );
		$this->assertTrue( is_null($record['last']) );
		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_float($record['elapsed']) );
		$this->assertTrue( is_array($record['last']) );
		sleep(1);
		$record = $mr->rec();
		sleep(1);
		$record = $mr->rec();

		$this->assertTrue( is_file(__DIR__.'/dist/record.txt') );
		$stdout = ob_get_clean();
		$this->assertTrue( !strlen($stdout) );
	}

	/**
	 * microtime recording as CSV test
	 */
	public function testRecordeMicrotimeAsCsv(){
		@unlink(__DIR__.'/dist/record.csv');
		ob_start();
		$mr = new tomk79\microtimeRecorder(__DIR__.'/dist/record.csv');
		$this->assertTrue( is_object($mr) );

		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_array($record) );
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_null($record['elapsed']) );
		$this->assertTrue( is_null($record['last']) );
		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_float($record['elapsed']) );
		$this->assertTrue( is_array($record['last']) );
		sleep(1);
		$record = $mr->rec();
		sleep(1);
		$record = $mr->rec();

		$this->assertTrue( is_file(__DIR__.'/dist/record.csv') );
		$stdout = ob_get_clean();
		$this->assertTrue( !strlen($stdout) );
	}

	/**
	 * microtime recording as STDOUT test
	 */
	public function testRecordeMicrotimeAsStdout(){
		$mr = new tomk79\microtimeRecorder(true);
		ob_start();
		$this->assertTrue( is_object($mr) );

		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_array($record) );
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_null($record['elapsed']) );
		$this->assertTrue( is_null($record['last']) );
		sleep(1);
		$record = $mr->rec();
		$this->assertTrue( is_float($record['microtime']) );
		$this->assertTrue( is_float($record['elapsed']) );
		$this->assertTrue( is_array($record['last']) );
		sleep(1);
		$record = $mr->rec();
		sleep(1);
		$record = $mr->rec();

		$stdout = ob_get_clean();
		// var_dump($stdout);
		$this->assertFalse( !strlen($stdout) );
	}

}
