# php-microtime-recorder
処理中の microtime を記録してボトルネックを探す デバッグライブラリです。

## インストール - Install

```
$ composer require tomk79/microtime-recorder
```


## 使い方 - Usage

```php
<?php
$mr = new tomk79\microtimeRecorder( '/path/to/record.txt' );

$mr->rec(); // Save initial record

for( $i = 0; $i < 10000; $i ++ ){}

$mr->rec(); // Save 2nd record
```

はじめに、 `tomk79\microtimeRecorder` のインスタンス `$mr` を作ります。
以降、 `$mr->rec()` を呼び出すたびに、その時点の記録が保存されます。

コンストラクタの第1引数には、次の値を指定できます。

- String `/path/to/record.txt` = 出力先ファイルのパスを指定した場合、テキストファイルに記録を保存します。
- String `/path/to/record.tsv` = テキストファイルへの出力と同じですが、特にファイルの拡張子が `.tsv` の場合は、TSV形式で出力されます。
- String `/path/to/record.csv` = テキストファイルへの出力と同じですが、特にファイルの拡張子が `.csv` の場合は、CSV形式で出力されます。
- Boolean `true` = ファイル出力の代わりに、 標準出力へレポートします。
- Boolean `false` または `null` または 省略 = 記録しません。プロダクト版コードに `$mr->rec()` の記述を残したい場合、 普段は `false` か `null` を与えておけば、 `tomk79/microtime-recorder` は何もしなくなります。


### 出力されるデータ

出力されるデータには、次の情報が含まれています。
データは、 1回の `$mr->rec()` の実行につき1行出力されます。

### Process ID

実行時のプロセスIDを格納します。

### elapsed

1回前の `$mr->rec()` が実行されてから経過した秒数を格納します。
インスタンスが生成されてから初めて実行された `$mr->rec()` では、 スクリプトが開始されてから経過した秒数が格納されます。

### FILE

`$mr->rec()` が実行されたファイルのパスを格納します。

### LINE

`$mr->rec()` が実行された行番号を格納します。


## テスト - Test

```
$ cd (project directory)
$ php ./vendor/phpunit/phpunit/phpunit
```


## 更新履歴 - Change Log

### tomk79/microtime-recorder v0.1.0 (2019年1月20日)

- Initial release.


## ライセンス - License

MIT License


## 作者 - Author

- (C)Tomoya Koyanagi <tomk79@gmail.com>
- website: <https://www.pxt.jp/>
- Twitter: @tomk79 <https://twitter.com/tomk79/>
