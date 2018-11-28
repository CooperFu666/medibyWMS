<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/9/20
 * Time: 10:18
 */

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class GoodsManageExport implements FromCollection,WithHeadings,WithTitle,WithMapping
{
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function collection()
    {
        // TODO: Implement collection() method.
        return $this->model->get([
            'deal_number',
            'storehouse',
            'version',
            'brand',
            'batch_no',
            'valid_at',
            'reg_no',
            'purchase_contract_no',
            'storage_at',
            'storage_operator',
            'sale_contract_no',
            'client',
            'client_province',
            'client_city',
            'client_district',
            'out_at',
            'out_operator',
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            '货号',
            '库房',
            '型号',
            '品牌',
            '批号',
            '生产日期/有效期',
            '注册证号',
            '采购合同号',
            '入库时间',
            '入库操作者',
            '销售合同号',
            '客户',
            '客户所在身份',
            '客户所在城市',
            '客户所在地区',
            '出库时间',
            '出库操作者',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // TODO: Implement title() method.
        return '商品管理';
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // TODO: Implement map() method.
        return [
            $row->deal_number,
            $row->storehouse,
            $row->version,
            $row->brand,
            $row->batch_no,
            !empty($row->valid_at)?dateFormat($row->valid_at):'--',
            $row->reg_no,
            $row->purchase_contract_no,
            !empty($row->storage_at)?dateFormat($row->storage_at):'--',
            $row->storage_operator,
            $row->sale_contract_no,
            $row->client,
            $row->client_province,
            $row->client_city,
            $row->client_district,
            !empty($row->out_at)?dateFormat($row->out_at):'--',
            $row->out_operator,
        ];
    }
}