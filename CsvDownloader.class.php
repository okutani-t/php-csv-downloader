<?php
/**
 * CSVのダウンロードを行うクラス
 *
 * @access public
 * @author okutani
 * @category DownLoader
 * @package Class
 */
class CsvDownLoader
{
    /**
     * @var string $fName CSVファイルの名前
     * @var array $hList 出力されるヘッダーのリスト
     * @var array $records 出力されるレコードのリスト
     */
    private $fName   = "";
    private $hList   = array();
    private $records = array();

    /**
     * 自身のインスタンスを生成
     * @access public
     * @return object new self
     */
    public static function _() {
        return new self;
    }

    /**
     * ファイル名を追加するセッター
     *
     * @access public
     * @param string $fName 保存したいファイル名を入力
     * @return object $this
     */
    public function setFileName($fName="")
    {
        // 空チェック
        if ($fName === "") trigger_error("empty fName!", E_USER_NOTICE);
        // .csvが入っていなかったら添付
        if (!preg_match("/.+\.csv/", $fName)) {
            $fName .= ".csv";
        }

        $this->fName = $fName;

        return $this;
    }

    /**
     * ヘッダー情報を追加するセッター
     *
     * @access public
     * @param array $hList ヘッダーが格納された配列を入力
     */
    public function setHeadList($hList=array())
    {
        $this->hList = $hList;

        return $this;
    }

    /**
     * レコード情報を追加するセッター
     * レコードが1つでも必ず2次元配列で渡す
     *
     * @access public
     * @param array $records 2次元で渡す
     */
    public function setRecords($records=array())
    {
        $this->records = $records;

        return $this;
    }

    /**
     * 連想配列を使ったレコードをソートする関数
     * sortRecordsByUsingKeys("hoge","huga","piyo")とすることで、その順番で連想配列をソーティングする
     * 呼び出し前にsetRecords()しておく必要がある
     * setHeadList()でセットするヘッダーの順番と同じにしておくと良い
     *
     * @access public
     * @param string $key_names 複数の引数を渡せる
     * @return object $this
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

        return $this;
    }

    /**
     * CSVダウンロード実行部
     *
     * @access public
     */
    public function execute()
    {
        // 空チェック
        if ($this->fName === "" || empty($this->hList) || empty($this->records)) {
            trigger_error("empty fName or hList or records!", E_USER_NOTICE);
        }

        // ヘッダーとレコードのエンコーディング処理
        $this->csvEcoding();

        // CSV初期設定
        ini_set('memory_limit', '256M');
        header('Content-Type: application/octet-stream; charset=Shift_JIS');
        header('Content-Disposition: attachment; filename='.$this->fName);
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: public');
        header('Pragma: public');

        // ヘッダーの書き出し
        echo $this->hList;

        // レコードの書き出し
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

}
