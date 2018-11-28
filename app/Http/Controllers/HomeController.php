<?php

namespace App\Http\Controllers;

use App\Exports\GoodsManage;
use App\Exports\GoodsManageExport;
use App\Models\BsClient;
use App\Models\BsCompany;
use App\Models\BsGoods;
use App\Models\BsPurchaseType;
use App\Models\BsRecords;
use App\Models\BsRecordsGoodsRelations;
use App\Models\BsSeller;
use App\Models\BsStorehouse;
use App\Models\BsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function goodsIn()
    {
        $user = Auth::user();
        $storehouseList = DB::table('bs_storehouse')->where('id', $user->storehouse_id)->pluck('name', 'id');
        $purchaseTypeList = BsPurchaseType::pluck('name', 'id');
        return view('frontend.home.goodsIn', [
            'purchaseTypeList' => $purchaseTypeList,
            'storehouseList' => $storehouseList,
        ]);
    }

    public function goodsInSave(Request $request)
    {
        $time = time();
        $mTime = microtime(true);
        $includeBrandList = [];
        $cTime = $mTime - session('time');
        if ($cTime < 5) {   //5秒内不能重复提交
            $flag = 2;
        } else {
            $user = Auth::user();
            $goodsList = $request->get('goodsList');
            $records = $request->get('records');
            $records['purchase_contract_no'] = strtoupper($records['purchase_contract_no']);
            DB::beginTransaction();
            try {
                foreach ($goodsList as $goods) {
                    $includeBrandList[] = $goods['brand'];
                }
                $recordsModel = new BsRecords();
                $recordsModel->contract = $records['purchase_contract_no'];
                $recordsModel->storehouse = $records['storehouse'];
                $recordsModel->storehouse_id = $records['storehouse_id'];
                $recordsModel->include_brand = implode(',', array_unique($includeBrandList));
                $recordsModel->type = BsGoods::STATUS_IN;
                $recordsModel->goods_no = count($goodsList);
                $recordsModel->operator = $user->nickname;
                $recordsModel->purchase_type = $records['purchase_type'];
                $recordsModel->purchase_type_id = $records['purchase_type_id'];
                $recordsModel->remark = ifValueEmpty($records['remark'], '');
                $recordsModel->updated_at = $time;
                $recordsModel->created_at = $time;
                $recordsModel->save();
                $recordsId = DB::getPdo()->lastInsertId();
                foreach ($goodsList as $goods) {
                    $goodsModel = new BsGoods();
                    $goodsModel->deal_number = $goods['deal_number'];
                    $goodsModel->storehouse = $records['storehouse'];
                    $goodsModel->storehouse_id = $records['storehouse_id'];
                    $goodsModel->version = $goods['version'];
                    $goodsModel->brand = $goods['brand'];
                    $goodsModel->batch_no = $goods['batch_no'];
                    $goodsModel->valid_at = strtotime($goods['valid_at']);
                    $goodsModel->reg_no = $goods['reg_no'];
                    $goodsModel->purchase_contract_no = $records['purchase_contract_no'];
                    $goodsModel->storage_at = $time;
                    $goodsModel->storage_operator = $user->nickname;
                    $goodsModel->purchase_type = $records['purchase_type'];
                    $goodsModel->purchase_type_id = $records['purchase_type_id'];
                    $goodsModel->status = BsGoods::STATUS_IN;
                    $goodsModel->updated_at = $time;
                    $goodsModel->created_at = $time;
                    $goodsModel->save();
                    $recordsGoodsRelationsModel = new BsRecordsGoodsRelations();
                    $recordsGoodsRelationsModel->records_id = $recordsId;
                    $recordsGoodsRelationsModel->goods_deal_number = $goods['deal_number'];
                    $recordsGoodsRelationsModel->save();
                }
                $flag = 1;
                DB::commit();
            } catch (\Exception $e) {
                $flag = 0;
                DB::rollBack();
            }
        }
        session(['time' => $mTime]);
        return returnJson(['flag' => $flag]);
    }

    /**
     * 生成货号
     */
    public function generateDealNumber($brandId, $num, $dealNumber)
    {
        $oldDay = '';
        $dealNumberList = [];
        $goods = BsGoods::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->first();
        if (!empty($goods)) {
            $oldDay = substr($goods->deal_number, -12, 6);
        }
        $currentDay = substr(date('Ymd', time()), -6, 6);

        if (!empty($dealNumber)) {
            $foot = substr($dealNumber, -6, 6);
            $startNum = $brandId . $currentDay . $foot;
        } else {
            if (!empty($oldDay) && $oldDay == $currentDay) {
                $foot = substr($goods->deal_number, -6, 6);
                $startNum = $brandId . $currentDay . $foot;
            } else {
                $startNum = $brandId . $currentDay . '000000';
            }
        }

        for ($i = 0; $i < $num; $i++) {
            $dealNumberList[] = ++$startNum;
        }
        return returnJson($dealNumberList);
    }

    public function generateDealNumberTxt(Request $request)
    {
        $txt = '';
        $dealNumberList = $request->post('dealNumberList');
        $dealNumberFile = fopen(__DIR__ . "/../../../public/storage/frontend/goodsIn/dealNumber.txt", "w");
        foreach ($dealNumberList as $dealNumber) {
            $txt .= "{$dealNumber}\r\n";
        }
        fwrite($dealNumberFile, $txt);
        fclose($dealNumberFile);
        return returnJson(['flag' => 1]);
    }

    public function downloadDealNumberTxt()
    {
        $file = __DIR__ . "/../../../public/storage/frontend/goodsIn/dealNumber.txt";
        $fileName = basename($file);  //获取文件名
        header("Content-Type:application/octet-stream");
        header("Content-Disposition:attachment;filename=".$fileName);
        header("Accept-ranges:bytes");
        header("Accept-Length:".filesize($file));
        $h = fopen($file, 'r');//打开文件
        return fread($h, filesize($file));
    }

    public function goodsOut()
    {
        $companyList = BsCompany::with(['seller'])->get();
        $clientList = BsClient::pluck('name', 'id');
        return view('frontend.home.goodsOut', [
            'companyList' => $companyList,
            'clientList' => $clientList,
        ]);
    }

    public function goodsOutSave(Request $request)
    {
        $time = time();
        $mTime = microtime(true);
        $cTime = $mTime - session('time');
        if ($cTime < 5) {   //5秒内不能重复提交
            $flag = 2;
        } else {
            $user = Auth::user();
            $data = $request->all();
            $data['record']['sale_contract_no'] = strtoupper($data['record']['sale_contract_no']);
            $client = BsClient::where('id', $data['record']['client_id'])->first();
            DB::beginTransaction();
            try {
                $includeBrandList = implode(',', array_unique(BsGoods::whereIn('deal_number', $data['dealNumberList'])->pluck('brand')->toArray()));
                $recordsModel = new BsRecords();
                $recordsModel->contract = $data['record']['sale_contract_no'];
                $recordsModel->storehouse = BsStorehouse::where('id', $user->storehouse_id)->pluck('name')->first();
                $recordsModel->storehouse_id = $user->storehouse_id;
                $recordsModel->include_brand = $includeBrandList;
                $recordsModel->type = BsGoods::STATUS_OUT;
                $recordsModel->goods_no = count($data['dealNumberList']);
                $recordsModel->seller = $data['record']['seller'];
                $recordsModel->operator = $user->nickname;
                $recordsModel->express_no = !empty($data['record']['express_no']) ? $data['record']['express_no'] : 0;
                $recordsModel->company_id = $data['record']['company_id'];
                $recordsModel->company = $data['record']['company'];
                $recordsModel->client = $client->name;
                $recordsModel->client_province = $client->province;
                $recordsModel->client_city = $client->city;
                $recordsModel->client_district = $client->district;
                $recordsModel->remark = ifValueEmpty($data['record']['remark'], '');
                $recordsModel->updated_at = $time;
                $recordsModel->created_at = $time;
                $recordsModel->save();
                $recordsId = DB::getPdo()->lastInsertId();
                foreach ($data['dealNumberList'] as $dealNumber) {
                    BsGoods::where('deal_number', $dealNumber)->update([
                        'sale_contract_no' => $data['record']['sale_contract_no'],
                        'company_id' => $data['record']['company_id'],
                        'company' => $data['record']['company'],
                        'client_id' => $client->id,
                        'client' => $client->name,
                        'client_province' => $client->province,
                        'client_city' => $client->city,
                        'client_district' => $client->district,
                        'seller' => $data['record']['seller'],
                        'seller_id' => $data['record']['seller_id'],
                        'out_at' => $time,
                        'out_operator' => $user->nickname,
                        'status' => BsGoods::STATUS_OUT,
                        'updated_at' => $time,
                    ]);
                    $recordsGoodsRelationsModel = new BsRecordsGoodsRelations();
                    $recordsGoodsRelationsModel->records_id = $recordsId;
                    $recordsGoodsRelationsModel->goods_deal_number = $dealNumber;
                    $recordsGoodsRelationsModel->save();
                }
                DB::commit();
                $flag = 1;
            } catch (\Exception $e) {
                DB::rollBack();
                $flag = 0;
            }
        }
        session(['time' => $mTime]);
        return returnJson(['flag' => $flag]);
    }

    public function isExistClient($clientName)
    {
        $flag = 0;
        $isExists = BsClient::where('name', $clientName)->exists();
        if ($isExists) {
            $flag = 1;
        }
        return returnJson(['flag' => $flag]);
    }

    public function addClient(Request $request)
    {
        $time = time();
        $data = $request->all();
        $client = new BsClient();
        $client->name = $data['addCompany'];
        $client->province = $data['addProvince'];
        $client->province_id = $data['addProvinceId'];
        $client->city = $data['addCity'];
        $client->city_id = $data['addCityId'];
        $client->district = $data['addDistrict'];
        $client->district_id = $data['addDistrictId'];
        $client->updated_at = $time;
        $client->created_at = $time;
        $flag = $client->save();
        $clientId = DB::getPdo()->lastInsertId();
        return returnJson(['flag' => $flag, 'clientId' => $clientId]);
    }

    public function cancelOut(Request $request, $recordId) {
        $time = time();
        $data = $request->all();
        DB::beginTransaction();
        $goodsDealNumberList = BsRecordsGoodsRelations::where('records_id', $recordId)->pluck('goods_deal_number');
        try {
            BsRecords::where('id', $recordId)->update([
                'cancel_reason' => $data['cancel_reason'],
                'is_cancel' => BsRecords::CANCELLED,
                'updated_at' => $time,
            ]);
            BsGoods::whereIn('deal_number', $goodsDealNumberList)->update([
                'status' => BsGoods::STATUS_IN,
                'sale_contract_no' => 0,
                'out_at' => 0,
                'out_operator' => '',
                'client' => 0,
                'client_id' => 0,
                'client_province' => '',
                'client_city' => '',
                'client_district' => '',
                'updated_at' => $time,
            ]);
            DB::commit();
            $flag = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $flag = 0;
        }
        return returnJson(['flag' => $flag]);
    }

    public function cancelIn(Request $request, $recordId) {
        $data = $request->all();
        $time = time();
        DB::beginTransaction();
        $goodsDealNumberList = BsRecordsGoodsRelations::where('records_id', $recordId)->pluck('goods_deal_number');
        if (!BsGoods::whereIn('deal_number', $goodsDealNumberList)->where('status', BsGoods::STATUS_OUT)->exists()) {
            try {
                BsRecords::where('id', $recordId)->update([
                    'cancel_reason' => $data['cancel_reason'],
                    'is_cancel' => BsRecords::CANCELLED,
                    'updated_at' => $time,
                ]);
                BsGoods::whereIn('deal_number', $goodsDealNumberList)->update([
                    'is_del' => BsGoods::DELETED,
                    'updated_at' => $time,
                ]);
                DB::commit();
                $flag = 1;
            } catch (\Exception $e) {
                DB::rollBack();
                $flag = 0;
            }
        } else {
            $flag = 2;  //提示“撤销入库失败！此次入库商品中已有商品出库！”
        }
        return returnJson(['flag' => $flag]);
    }

    public function getGoodsInfo($dealNumber)
    {
        $data['flag'] = 0;
        $goodsInfo = BsGoods::where(['deal_number' => $dealNumber])->first();
        if (!empty($goodsInfo)) {
            if ($goodsInfo->status == BsGoods::STATUS_OUT) {
                $data['flag'] = 2;  //提示“商品已出库！”
            } else {
                $data['flag'] = 1;  //可出库
                $goodsInfo->valid_at = date('Y-m-d', $goodsInfo->valid_at);
                $data['goods'] = $goodsInfo;
            }
            if ($goodsInfo->is_del == BsGoods::DELETED) {
                $data['flag'] = 3;  //提示“商品已撤销入库！”
            }
        }
        return returnJson($data);
    }

    public function goodsManage()
    {
        $search = Input::get('search');
        $goodsModel = new BsGoods();
        $goodsModel = $goodsModel->where('is_del', BsGoods::DELETE_NOT);
        $sellerDB = DB::table('bs_seller')->join('bs_company', 'bs_seller.company_id', 'bs_company.id');
        if (!empty($search['brand']) && $search['brand'] != '全部') {
            $goodsModel = $goodsModel->where('brand', $search['brand']);
        }
        if (!empty($search['status']) && $search['status'] != '全部') {
            if ($search['status'] == '已出库') {
                $goodsModel = $goodsModel->where('status', BsGoods::STATUS_OUT);
            } else {
                $goodsModel = $goodsModel->where('status', BsGoods::STATUS_IN);
            }
        }
        if (!empty($search['company']) && $search['company'] != '全部') {
            $goodsModel = $goodsModel->where('company', $search['company']);
            $sellerDB->where('bs_company.name', $search['company']);

        }
        if (!empty($search['seller']) && $search['seller'] != '全部') {
            $goodsModel = $goodsModel->where('seller', $search['seller']);
        }
        if (!empty(Input::get('cho_Province')) && Input::get('cho_Province') != '请选择省份') {
            $goodsModel = $goodsModel->where('client_province', Input::get('cho_Province'));
        }
        if (!empty(Input::get('cho_City')) && Input::get('cho_City') != '请选择城市') {
            $goodsModel = $goodsModel->where('client_city', Input::get('cho_City'));
        }
        if (!empty(Input::get('cho_Area')) && Input::get('cho_Area') != '请选择地区') {
            $goodsModel = $goodsModel->where('client_district', Input::get('cho_Area'));
        }
        if (!empty($search['client']) && $search['client'] != '全部') {
            $goodsModel = $goodsModel->where('client', $search['client']);
        }
        if (!empty($search['no'])) {
            $goodsModel = $goodsModel->where(function ($query) use ($search) {
                $query->orWhere('version', $search['no'])->orWhere('batch_no', $search['no'])->orWhere('purchase_contract_no', $search['no'])
                    ->orWhere('sale_contract_no', $search['no'])->orWhere('reg_no', $search['no']);
            });
        }
        if (!empty($search['time'])) {
            $timeArr = explode('到', $search['time']);
            $timeArr[0] = strtotime($timeArr[0]);
            $timeArr[1] = strtotime($timeArr[1]);
            if ($search['time_type'] == '按入库时间') {
                $goodsModel = $goodsModel->whereBetween('storage_at', [$timeArr[0], $timeArr[1]]);
            } else {
                $goodsModel = $goodsModel->whereBetween('out_at', [$timeArr[0], $timeArr[1]]);
            }
        }
        if (Input::has('export')) {
            return Excel::download(new GoodsManageExport($goodsModel), "商品管理" . date('YmdHis', time()) . ".xlsx");
        }
        $goodsList = $goodsModel->orderBy('updated_at', 'DESC')->paginate(15);
        //分页条件保持
        $goodsList->appends(array(
            'search[brand]' => issetParams(['search' => 'brand'], '全部'),
            'search[status]' => issetParams(['search' => 'status'], '全部'),
            'search[company]' => issetParams(['search' => 'company'], '全部'),
            'search[seller]' => issetParams(['search' => 'seller'], '全部'),
            'search[time_type]' => issetParams(['search' => 'time_type'], '按入库时间'),
            'search[time]' => issetParams(['search' => 'time'], ''),
            'cho_Province' => issetParams('cho_Province', ''),
            'cho_City' => issetParams('cho_City', ''),
            'cho_Area' => issetParams('cho_Area', ''),
            'search[client]' => issetParams(['search' => 'client'], '全部'),
        ));
        $companyList = BsCompany::with(['seller'])->get();
        $clientList = BsClient::all();
        $sellerList = $sellerDB->pluck('bs_seller.name', 'bs_seller.id');
        return view('frontend.home.goodsManage', [
            'companyList' => $companyList,
            'clientList' => $clientList,
            'goodsList' => $goodsList,
            'sellerList' => $sellerList,
        ]);
    }

    public function inOutRecords()
    {
        $search = Input::get('search');
        $companyList = BsCompany::with(['seller'])->get();
        $clientList = BsClient::pluck('name', 'id');
        $userList = BsUsers::get(['nickname']);
        $sellerDB = DB::table('bs_seller')->join('bs_company', 'bs_seller.company_id', 'bs_company.id');
        $model = BsRecordsGoodsRelations::with(['records', 'goods'])->where(function ($query) use ($search, $sellerDB) {
            $query->orWhere(function ($query) use ($search, $sellerDB) {
                if (!empty($search['no'])) {
                    $query->orWhereHas('records', function ($query) use ($search) {
                        $query->where('contract', $search['no']);
                    })->orWhereHas('goods', function ($query) use ($search) {
                        $query->where('version', $search['no']);
                    });
                }
                $query->whereHas('records', function ($query) use ($search, $sellerDB) {
                    if (!empty($search['purchase_type']) && $search['purchase_type'] != '全部') {
                        $query->where('purchase_type', $search['purchase_type']);
                    }
                    if (!empty($search['operator']) && $search['operator'] != '全部') {
                        $query->where('operator', $search['operator']);
                    }
                    if (!empty($search['is_cancel']) && $search['is_cancel'] != '全部') {
                        if ($search['is_cancel'] == '已撤销') {
                            $query->where('is_cancel', BsRecords::CANCELLED);
                        } else {
                            $query->where('is_cancel', BsRecords::CANCELED_NOT);
                        }
                    }
                    if (!empty($search['brand']) && $search['brand'] != '全部') {
                        $query->where('include_brand', $search['brand']);
                    }
                    if (!empty($search['type']) && $search['type'] != '全部') {
                        if ($search['type'] == '出库') {
                            $query->where('type', BsRecords::TYPE_OUT);
                        } else {
                            $query->where('type', BsRecords::TYPE_IN);
                        }
                    }
                    if (!empty($search['company']) && $search['company'] != '全部') {
                        $query->where('company', $search['company']);
                        $sellerDB->where('bs_company.name', $search['company']);
                    }
                    if (!empty($search['seller']) && $search['seller'] != '全部') {
                        $query->where('seller', $search['seller']);
                    }
                    if (!empty(Input::get('cho_Province')) && Input::get('cho_Province') != '请选择省份') {
                        $query->where('client_province', Input::get('cho_Province'));
                    }
                    if (!empty(Input::get('cho_City')) && Input::get('cho_City') != '请选择城市') {
                        $query->where('client_city', Input::get('cho_City'));
                    }
                    if (!empty(Input::get('cho_Area')) && Input::get('cho_Area') != '请选择地区') {
                        $query->where('client_district', Input::get('cho_Area'));
                    }
                    if (!empty($search['client']) && $search['client'] != '全部') {
                        $query->where('client', $search['client']);
                    }
                    if (!empty($search['time'])) {
                        $timeArr = explode('到', $search['time']);
                        $timeArr[0] = strtotime($timeArr[0]);
                        $timeArr[1] = strtotime($timeArr[1]);
                        $query->whereBetween('created_at', [$timeArr[0], $timeArr[1]]);
                    }
                });
            });
        })->groupBy('records_id')->orderBy('id', 'DESC');
        $recordsList = $model->paginate(15);
        //分页条件保持
        $recordsList->appends(array(
            'search[operator]' => issetParams(['search' => 'operator'], '全部'),
            'search[brand]' => issetParams(['search' => 'brand'], '全部'),
            'search[type]' => issetParams(['search' => 'type'], '全部'),
            'search[company]' => issetParams(['search' => 'company'], '全部'),
            'search[seller]' => issetParams(['search' => 'seller'], '全部'),
            'search[time_type]' => issetParams(['search' => 'time_type'], '按入库时间'),
            'search[time]' => issetParams(['search' => 'time'], ''),
            'cho_Province' => issetParams('cho_Province', ''),
            'cho_City' => issetParams('cho_City', ''),
            'cho_Area' => issetParams('cho_Area', ''),
            'search[client]' => issetParams(['search' => 'client'], '全部'),
        ));
        $purchaseTypeList = BsPurchaseType::pluck('name', 'id');
        $sellerList = $sellerDB->pluck('bs_seller.name', 'bs_seller.id');
        return view('frontend.home.inOutRecords', [
            'sellerList' => $sellerList,
            'purchaseTypeList' => $purchaseTypeList,
            'userList' => $userList,
            'companyList' => $companyList,
            'clientList' => $clientList,
            'recordsList' => $recordsList,
        ]);
    }

    public function alterPurchaseType(Request $request)
    {
        $time = time();
        $data = $request->all();
        $flag = BsRecords::where('id', $data['record_id'])->update([
            'purchase_type' => $data['purchase_type'],
            'purchase_type_id' => $data['purchase_type_id'],
            'updated_at' => $time,
        ]);
        return returnJson(['flag' => $flag]);
    }

    public function alterContract(Request $request)
    {
        $time = time();
        $data = $request->all();
        $data['contract'] = strtoupper($data['contract']);
        $dealNumberList = BsRecordsGoodsRelations::where('records_id', $data['record_id'])->pluck('goods_deal_number');
        DB::beginTransaction();
        try {
            $attr['updated_at'] = $time;
            if ($data['type'] == 'purchase') {
                $attr['purchase_contract_no'] = $data['contract'];
            } else {
                $attr['sale_contract_no'] = $data['contract'];
            }
            if (!empty($dealNumberList)) {
                BsGoods::whereIn('deal_number', $dealNumberList)->update($attr);
            }
            $flag = BsRecords::where('id', $data['record_id'])->update([
                'contract' => $data['contract'],
                'updated_at' => $time,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $flag = 0;
        }
        return returnJson(['flag' => $flag, 'contract' => $data['contract']]);
    }

    public function alterExpressNo(Request $request)
    {
        $time = time();
        $data = $request->all();
        $flag = BsRecords::where('id', $data['record_id'])->update([
            'express_no' => $data['express_no'],
            'updated_at' => $time,
        ]);
        return returnJson(['flag' => $flag]);
    }

    public function changePassword(Request $request)
    {
        $flag = 0;
        $userId = Auth::user()->id;
        $data = Input::all();
        if ($data['newPassword'] == $data['newPasswordRepeat']) {
            $user = BsUsers::where('id', $userId)->first();
            if (!empty($user)) {
                $isCorrect = password_verify($data['oldPassword'], $user->password);
                if ($isCorrect) {
                    $flag = BsUsers::where('id', $userId)->update([
                        'password' => bcrypt($data['newPassword']),
                        'updated_at' => time(),
                    ]);
                } else {
                    $flag = 2;
                }
            }
        }
        return returnJson(['flag' => $flag]);
    }
}
