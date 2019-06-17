<?php namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * 設定ベースでデータアクセスの抽象クラス
 * Class BaseModel
 * @package App
 */
class SalesBaseFormModel extends BaseModel implements IEditable {

    //use FormTrait, ImportTrait;
    protected $formKey = false;

    // 接続先DBを指定
    protected $connection = 'salesforce';

    /**
     * BaseFormModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // 設定からFillableを埋める
        if ($this->formKey) {
            $config = collect(config($this->formKey));
            if (!empty($config)) {
                $this->fillable = $config->keyBy('id')->keys()->toArray();
            }
        }

        parent::__construct($attributes);
    }
}
