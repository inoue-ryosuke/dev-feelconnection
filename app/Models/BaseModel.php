<?php namespace App\Models;

use App\ValidatorTrait;
use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormTrait;

/**
 * データアクセスの抽象クラス
 * Class BaseModel
 * @package App
 */
class BaseModel extends Model implements IEditable {

//    use ValidatorTrait, FormTrait, ImportTrait;
    use FormTrait;

    /**
     * @return string
     */
    public static function getTableName() {
        return (new static)->getTable();
    }
    // 各モデルで定義するSalesForce用項目
    protected static $salesforceAccessor = [
//            'cid', 
//			'name',
//			'h_buil'
    ];

    /**
     * フォーム値から値を取得する。
     * @param $formKey
     * @return 値
     */
    public function getValue($formKey) {
        if (isset($this->{$formKey})) {
            return $this->{$formKey};
        }
        return null;
    }

    /**
     * ID情報取得
     * @param integer $id ID
     * @param boolean $deleteFlg 削除フラグ
     * @return BaseModel|Taxes オブジェクト
     */
    public static function findById($id, $deleteFlg = false,$lock=false) {
        if (empty($id)) {
            return null;
        }
        $query = self::where(self::getTableName().'.id', $id);

        if($deleteFlg == true) {
            // 削除済みのデータを含める
            $query->withTrashed();
        }
        
        if($lock){
            $query->lockForUpdate();
        }
        $data = $query->first();

        return $data;
    }

    /**
     * IDからリスト情報取得
     * @param $ids
     * @param boolean $deleteFlg 削除フラグ
     * @return
     */
    public static function findListByIds($ids, $deleteFlg = false,$sortColumn = null,$order = null) {
        if (empty($ids)) {
            return null;
        }
        $query = self::whereIn(self::getTableName().'.id', $ids);

        if($deleteFlg == true) {
            // 削除済みのデータを含める
            $query->withTrashed();
        }
        
        if(!is_null($sortColumn) && !is_null($order)){
            $query->orderBy($sortColumn,$order);
        }
        
        $list = $query->get();

        return $list;
    }
}
