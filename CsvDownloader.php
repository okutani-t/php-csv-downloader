<?php
/**
 * CSVのダウンロードを行うクラス
 * 使い方
 * 1. $csv = new CsvDownLoader();
 * 2. $csv->addFileName("ファイル名"); #.csvがついてなくても自動で付与されます
 * 3. $csv->addHeadList(ヘッダーのリスト);
 * 4. $csv->addRecords(レコードのリスト); #レコードが1つでも必ず2次元配列で渡す
 * 5. $csv->sortRecordsByUsingKeys(レコードのKEY名); #レコードが連想配列だった場合利用
 * 6. $csv->runCsvDl();
 *
 * @access public
 * @author okutani
 * @category DownLoader
 * @package Class
 */
class CsvDownLoader
{
    private $fName = "";
    private $hList = array();
    private $records = array();
    /**
     * CSVダウンロード実行部
     *
     * @access public
     */
    public function runCsvDl()
    {
        // 引数が空ならreturn
        if ($this->fName == "" || empty($this->hList) || empty($this->records)) return;
        // ヘッダーとレコードのエンコーディング処理
        $this->csvEcoding();
        // CSV初期設定
        ini_set('memory_limit', '256M');
        header('Content-Type: application/octet-stream; charset=Shift_JIS');
        header('Content-Disposition: attachment; filename='.$this->fName);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: public');
        header('Pragma: public');
        // ヘッダー項目の出力
        echo $this->hList;
        // メインの内容の出力
        foreach($this->records as $value){
            echo $value;
        }
    }

    /**
     * メッセージリストのエンコーディング処理
     *
     * @access private
     */
    private function csvEcoding()
    {
        // エンコーダーの無名関数
        $encoder = function($ary){
            return mb_convert_encoding('"' . implode('","', $ary ) . '"' . "\n", "SJIS", "auto");
        };
        // ヘッダーのエンコーディング
        $this->hList = $encoder($this->hList);
        // レコードのエンコーディング
        for ($i = 0; $i < count($this->records); $i++) {
            $this->records[$i] = $encoder($this->records[$i]);
        }
    }

    /**
     * 保存するファイル名を追加するセッター
     *
     * @access public
     * @param string $fName 保存するファイル名を入力
     */
    public function addFileName($fName="")
    {
        if (!preg_match("/.+\.csv/", $fName)) {
            $fName .= ".csv";
        }
        $this->fName = $fName;
    }

    /**
     * ヘッダー情報を追加するセッター
     *
     * @access public
     * @param array $hList ヘッダーが格納された配列を入力
     */
    public function addHeadList($hList=array())
    {
        $this->hList = $hList;
    }

    /**
     * レコード情報を追加するセッター
     * レコードが1つでも必ず2次元配列で渡す
     *
     * @access public
     * @param array $records 2次元で渡す
     */
    public function addRecords($records=array())
    {
        $this->records = $records;
    }

    /**
     * 連想配列のキー名を元にソートするセッター
     * sortRecordsByUsingKeys("hoge","huga","piyo")とすることで、その順番で連想配列をソーティングする
     * 呼び出し前にaddRecords()しておく必要がある
     * addHeadList()でセットするヘッダーの順番と同じにしておく
     *
     * @access public
     * @param string $key_names args複数の文字列を渡せる
     */
    public function sortRecordsByUsingKeys(/*key_names*/)
    {
        $key_names = func_get_args();
        // エラー処理
        if (empty($key_names)) {
            trigger_error("empty keys!", E_USER_ERROR);
            return;
        }
        if (empty($this->records)) {
            trigger_error("empty records!", E_USER_ERROR);
            return;
        }

        $i = 0;
        foreach ($this->records as $record) {
            for($j = 0; $j < count($key_names); $j++){
                $rm_key_records[$i][] = $record[$key_names[$j]];
            }
            $i++;
        }
        $this->records = $rm_key_records;
    }

}
