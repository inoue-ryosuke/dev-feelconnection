<?php namespace App\Models;


/**
 * リクエストマージに特化したトレイト
 * Class RequestMergerTrait
 * GuardsAttributesを併用すること
 * @package App
 */
trait FormTrait
{

    /**
     * カラム値を取得する
     * @param null $key
     * @return array|mixed
     */
    public function getValue($key = null) {
        if (!isset($this->{$key})) {
            return null;
        }
        return $this->{$key};
    }

    /**
     * 入力リクエストをオブジェクトにマージする
     * @param $request 入力リクエスト値
     */
    public function mergeRequest($request)
    {
        if (is_array($request)) {
            $request = collect($request);
        }
        // salesforce用にテーブル名が変わっても、リクエストのキー名は__c無しのまま
        foreach ($this->getFillable() as $key) {
            // fillableはモデル側情報なので、__c付きになる恐れがある、そのため、keyから__cを外す
            $sfkey = $key; //__c付きのキー
            $fckey = preg_replace("#^(.+)(__c)$?","$1",$key);  // feelconnectionキー（__cなし)

            // __cなしキー名でリクエストに該当した場合、モデルのテーブル名が__c付きなら__cつきカラムへ
            if (isset($request[$fckey])) {
                $this->{$this->cKey($fckey)} = $request[$fckey];   
            } else if (array_key_exists($fckey, $request->all())) {
                $this->{$this->cKey($fckey)} = data_get($fckey, $request->all());
            }
        }
    }

    /**
     * チェックボックスの有無をチェックしてフラグを調整する。
     * @param $request 入力値
     * @param $targetColumn
     * @param mixed $notValue チェック状態でない場合は 0
     */
    public function mergeCheckbox($request, $targetColumn , $notValue = 0) {
        $checker = array_flip($targetColumn);
        foreach ($this->getFillable() as $key) {
            if (!isset($checker[$key])) {
                continue;
            }
            if (isset($request[$key])) {
                $this->{$key} = $request[$key];
            } else if (array_key_exists($key, $request->all())) {
                $this->{$key} = data_get($key, $request->all());
            } else {
                $this->{$key} = $notValue;
            }
        }
    }


    /**
     * 日付フォームのリクエストをモデルにマージする。
     * @param $request
     * @param $targetColumns
     * @param null $dataKey
     */
    public function mergeDateRequest($request, $targetColumns, $dataKey = null) {
        if (is_null($dataKey)) {
            foreach ($targetColumns as $key) {
                if (isset($request[$key . '_y']) && isset($request[$key . '_m']) && isset($request[$key . '_d'])
                    && isset($request[$key . '_h']) && isset($request[$key . '_i'])
                ) {
                    $this->{$key} = sprintf("%04d-%02d-%02d %02d:%02d:00",
                        $request[$key . '_y'], $request[$key . '_m'], $request[$key . '_d'],
                        $request[$key . '_h'], $request[$key . '_i']);

//                    logger("merge date input => ".$key.":".$this->{$key});
                } else if (isset($request[$key]) && isset($request[$key . '_h']) && isset($request[$key . '_i'])) {
                    $this->{$key} = sprintf("%s %02d:%02d:00",
                        $request[$key], $request[$key . '_h'], $request[$key . '_i']);
                } else if (isset($request[$key . '_y']) && isset($request[$key . '_m']) && isset($request[$key . '_d'])) {
                    $this->{$key} = sprintf("%04d-%02d-%02d 00:00:00",
                        $request[$key . '_y'], $request[$key . '_m'], $request[$key . '_d']);
                } else if (isset($request[$key])) {
                    $this->{$key} = $request[$key];
                }
                if (isset($this->{$key}) && !isset($request[$key])) {
//                    logger("input merge => ".$key.":".$this->{$key});
                    Input::merge([$key => $this->{$key}]);
                }

            }
        } else {
            // 共通カラムからデータ抜き出し
            $data = (array) json_decode($this->{$dataKey}, true);
            foreach ($targetColumns as $key) {
                if (isset($request[$key . '_y']) && isset($request[$key . '_m']) && isset($request[$key . '_d'])
                    && isset($request[$key . '_h']) && isset($request[$key . '_i'])
                ) {
                    $data[$key] = sprintf("%04d-%02d-%02d %02d:%02d:00",
                        $request[$key . '_y'], $request[$key . '_m'], $request[$key . '_d'],
                        $request[$key . '_h'], $request[$key . '_i']);
                } else if (isset($request[$key . '_y']) && isset($request[$key . '_m']) && isset($request[$key . '_d'])) {
                    $data[$key] = sprintf("%04d-%02d-%02d 00:00:00",
                        $request[$key . '_y'], $request[$key . '_m'], $request[$key . '_d']);
                } else if (isset($request[$key])) {
                    $data[$key] = $request[$key];
                }
                if (isset($data[$key]) && !isset($request[$key])) {
                    Input::merge([$key => $data[$key]]);
                }
            }
            $this->{$dataKey} = json_encode($data);
        }

    }


    /**
     * 日付カラムから選択リスト向けの値を取得する
     * @param $key 日付カラム
     * @param $index 0:年/1:月/2:日/3:時/4:分
     * @return 対象情報
     */
    public function getDatetime($key, $index = null) {
//		logger($key);
        $targetDatetime = $this->{$key};
//		// 汎用配列に含まれる場合はそこから取り出す
//		if (empty($targetDatetime) && !empty($this->data)) {
//			$record = (array) json_decode($this->data, true);
//			$targetDatetime = array_selector($key, $record);
//		}
        // 空なら null を返す
        if (empty($targetDatetime)) {
            return null;
        }
        // もしインデックスの指定がなければ全体を返す
        if (is_null($index)) {
            return $targetDatetime;
        }
        // 分割して対象の値を取得
        $splitTargetDatetime = preg_split('/[\s:\/\-]/', $targetDatetime);
        if (count($splitTargetDatetime) <= $index) {
            return null;
        }
        return $splitTargetDatetime[$index];
    }

}
