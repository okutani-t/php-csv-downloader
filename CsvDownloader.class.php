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
     *
     * @access public
     * @param array $records 1次配列か2次配列で渡す
     */
    public function setRecords($records=array())
    {
        $this->records = $records;

        return $this;
    }

    /**
     * 連想配列を使ったレコードをソートする関数
     * 渡した配列の順番で連想配列をソーティングする
     * 呼び出し前にsetRecords()しておく必要がある
     * setHeadList()でセットするヘッダーの順番と同じにしておくと良い
     *
     * @access public
     * @param string $keyNames
     * @return object $this
     */
    public function sortRecordsByUsingKeys($keyNames=array())
    {
        // エラー処理
        if (empty($keyNames) || !is_array($keyNames)) {
            trigger_error("error key args!", E_USER_ERROR);
        }
        if (empty($this->records)) {
            trigger_error("empty records!", E_USER_ERROR);
        }

        // レコードが1次元配列か2次元配列かチェックして個別に並べ替え
        if ($this->array_depth($this->records) === 1) {
            foreach ($keyNames as $value) {
                $rmKeyRecords[] = $this->records[$value];
            }
        } elseif ($this->array_depth($this->records) === 2) {
            $i = 0;
            foreach ($this->records as $record) {
                for ($j = 0; $j < count($keyNames); $j++) {
                    $rmKeyRecords[$i][] = $record[$keyNames[$j]];
                }
                $i++;
            }
        }

        $this->records = $rmKeyRecords;

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
        if (is_array($this->records)) {
            foreach($this->records as $value){
                echo $value;
            }
        } else {
            echo $this->records;
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
        $encoder = function($arr){
            return mb_convert_encoding('"' . implode('","', $arr ) . '"' . "\n", "SJIS", "auto");
        };

        // ヘッダーのエンコーディング
        $this->hList = $encoder($this->hList);

        // レコードが1次元配列か2次元配列かチェックして個別にエンコーディング
        if ($this->array_depth($this->records) === 1) {
            $this->records = $encoder($this->records);
        } elseif ($this->array_depth($this->records) === 2) {
            for ($i = 0; $i < count($this->records); $i++) {
                $this->records[$i] = $encoder($this->records[$i]);
            }
        }
    }

    /**
     * 配列の深さを調べる
     *
     * @param  array $arr
     * @return int 配列の深さ
     */
    private function array_depth($arr, $depth=0){
        if( !is_array($arr)){
            return $depth;
        } else {
            $depth++;
            $tmp = array();
            foreach($arr as $value){
                $tmp[] = $this->array_depth($value, $depth);
            }
            return max($tmp);
        }
    }

}
