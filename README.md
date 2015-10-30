# CSV DOWNLOADER

PHPで使えるCSVのダウンローダー

---

## 使い方

1. $csv = new CsvDownLoader();
2. $csv->addFileName("ファイル名"); #.csvがついてなくても自動で付与されます
3. $csv->addHeadList(ヘッダーのリスト);
4. $csv->addRecords(レコードのリスト); #レコードが1つでも必ず2次元配列で渡す
5. $csv->sortRecordsByUsingKeys(レコードのKEY名); #レコードが連想配列だった場合利用
6. $csv->runCsvDl();
