<?php
/**
 * tomk79/microtime-recorder
 *
 * @author Tomoya Koyanagi <tomk79@gmail.com>
 */

namespace tomk79;

/**
 * tomk79/microtime-recorder core class
 *
 * @author Tomoya Koyanagi <tomk79@gmail.com>
 */
class microtimeRecorder{

	/** 記録の出力先 */
	private $path_record = null;

	/** 記録のフォーマット */
	private $record_format = 'txt';

	/** 記録を標準出力に送るか */
	private $flg_stdout = false;

	/** 1つ前の記録 */
	private $last_record = null;

	/**
	 * Constructor
	 *
	 * @param mixed $dist 出力先
	 */
	public function __construct( $dist ){
		if( is_string( $dist ) ){
			$this->path_record = $dist;
			if( preg_match( '/\.csv$/s', $dist ) ){
				$this->record_format = 'csv';
				$rec = '';
				$rec .= '"Process ID"'.",";
				$rec .= '"elapsed"'.",";
				$rec .= '"FILE"'.",";
				$rec .= '"LINE"'.",";
				$rec .= '"values"'."\r\n";
				error_log($rec, 3, $this->path_record);
			}elseif( preg_match( '/\.tsv$/s', $dist ) ){
				$this->record_format = 'tsv';
				$rec = '';
				$rec .= 'Process ID'."\t";
				$rec .= 'elapsed'."\t";
				$rec .= 'FILE'."\t";
				$rec .= 'LINE'."\t";
				$rec .= 'values'."\r\n";
				error_log($rec, 3, $this->path_record);
			}
		}elseif( $dist === true ){
			$this->flg_stdout = true;
		}
	}

    /**
     * microtime を記録する
	 * @param mixed $val 確認したい値(複数指定可)
	 * @return array 記録内容を含む連想配列
     */
    public function rec(){
		$record = current( debug_backtrace() );
		$record['microtime'] = microtime(true);
		if( is_array($this->last_record) && array_key_exists('last', $this->last_record) ){
			unset($this->last_record['last']);
		}
		$record['last'] = $this->last_record;
		$record['elapsed'] = null;
		if( is_array($this->last_record) && is_float($this->last_record['microtime']) ){
			// 1つ前の記録からの経過時間
			$record['elapsed'] = $record['microtime'] - $this->last_record['microtime'];
		}else{
			// 初回の記録は、 スクリプトの開始時点からの経過時間を報告する。
			$record['elapsed'] = $record['microtime'] - $_SERVER['REQUEST_TIME_FLOAT'];
		}

		$args = func_get_args();
		$record['values'] = '';
		foreach($args as $i=>$arg){
			if(count($args) > 1){
				$record['values'] .= '----- '.$i.' ----'."\r\n";
			}
			ob_start();
			var_dump($arg);
			$record['values'] .= ob_get_clean()."\r\n";
		}
		$record['values'] = trim($record['values']);

		if( $this->flg_stdout ){
			// 記録を標準出力する
			$stdout = '';
			$stdout .= '<div style="display: block; visibility: visible; border: 3px solid #000; background:#fff; color: #000; padding: 10px; font-size: 12px; line-height: 1.2;">'."\r\n";
			$stdout .= '<strong>[Microtime Recorder]</strong><br />'."\r\n";
			$stdout .= htmlspecialchars($record['elapsed']).' sec elapsed from '.( is_array($this->last_record) ? 'last record' : 'starting process' ).'.'."<br />\r\n";
			$stdout .= 'in '.htmlspecialchars($record['file']).' Line: '.htmlspecialchars($record['line'])."<br />\r\n";
			$stdout .= '<pre>'.htmlspecialchars($record['values']).'</pre>'."\r\n";
			$stdout .= '</div>'."\r\n";
			echo($stdout);
		}

		if( $this->path_record ){
			// 記録をファイルに出力する
			if( $this->record_format == 'csv' ){
				// csv
				$rec_csv = '';
				$rec_csv .= '"'.getmypid().'"'.",";
				$rec_csv .= '"'.sprintf("%.7f", $record['elapsed']).'"'.",";
				$rec_csv .= '"'.$this->escape_csv_val($record['file']).'"'.",";
				$rec_csv .= '"'.$this->escape_csv_val($record['line']).'"'.",";
				if( strlen($record['values']) ){
					$rec_csv .= '"'.$this->escape_csv_val($record['values']).'"';
				}
				$rec_csv .= "\r\n";
				error_log($rec_csv, 3, $this->path_record);
			}elseif( $this->record_format == 'tsv' ){
				// tsv
				$rec_tsv = '';
				$rec_tsv .= getmypid()."\t";
				$rec_tsv .= sprintf("%.7f", $record['elapsed'])."\t";
				$rec_tsv .= $record['file']."\t";
				$rec_tsv .= $record['line']."\t";
				if( strlen($record['values']) ){
					$rec_tsv .= '"'.$this->escape_csv_val($record['values']).'"';
				}
				$rec_tsv .= "\r\n";
				error_log($rec_tsv, 3, $this->path_record);
			}else{
				// txt
				$rec_txt = '';
				$rec_txt .= "\r\n";
				$rec_txt .= '---------'."\r\n";
				$rec_txt .= '[Microtime Recorder]'."\r\n";
				$rec_txt .= 'Process ID: '.getmypid()."\r\n";
				$rec_txt .= sprintf("%.7f", $record['elapsed']).' sec elapsed from '.( is_array($this->last_record) ? 'last record' : 'starting process' ).'.'."\r\n";
				$rec_txt .= 'in '.$record['file'].' Line: '.$record['line']."\r\n";
				if( strlen($record['values']) ){
					$rec_txt .= $record['values']."\r\n";
				}
				error_log($rec_txt, 3, $this->path_record);
			}
		}

		$this->last_record = $record;
        return $record;
    }

	/**
	 * CSVの値をエスケープする
	 */
	private function escape_csv_val( $val ){
		$val = preg_replace('/\"/s', '""', $val);
		return $val;
	}

}
