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
```

author: okutani
