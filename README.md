# PHP CSV DOWNLOADER

PHPで使えるCSVのダウンローダー

---

## 使い方

```php
// CsvDownloader読み込み
require_once(__DIR__."/CsvDownLoader.php");

CsvDownLoader::_()->setFileName("ファイル名") #.csvがついてなくても自動で付与されます
                  ->setHeadList(ヘッダーのリスト)
                  ->setRecords(レコードのリスト) #レコードが1つでも必ず2次元配列で渡してください
                  ->sortRecordsByUsingKeys(レコードのKEY名...) #レコードが連想配列だった場合利用します
                  ->execute();
exit;
```
```php
// sample
// CsvDownloader読み込み
require_once(__DIR__."/CsvDownLoader.php");

// POST時動作
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // テストレコード
    $records = array(
                array("id"=>1,"name"=>"tanaka"),
                array("id"=>2,"name"=>"yamada"),
                array("id"=>3,"name"=>"nakano")
            );

    CsvDownLoader::_()->setFileName("test-csv")
                      ->setHeadList(array("name","id"))
                      ->setRecords($records)
                      ->sortRecordsByUsingKeys("name","id")
                      ->execute();
    exit;
}
```

連想配列じゃなくても問題なく動作します。

author: okutani
