<?php
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

    $csv = new CsvDownLoader();
    $csv->addFileName("test-csv"); #.csvがついてなくても自動で付与されます
    $csv->addHeadList(array("name","id"));
    $csv->addRecords($records);
    $csv->sortRecordsByUsingKeys("name","id");
    $csv->runCsvDl();
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>csv downloader test</title>
    </head>
    <style>
        body {
            width: 940px;
            margin: 0 auto;
        }
    </style>
    <body>
        <h1>csv downloaderのテスト</h1>
        <form action="" method="post">
            <input type="submit" name="name" value="ダウンロード">
        </form>
    </body>
</html>
