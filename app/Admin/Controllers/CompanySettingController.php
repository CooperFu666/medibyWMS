<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BsCompany;
use App\Models\BsSeller;
use App\Models\BsUsers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class CompanySettingController extends Controller
{
    use ModelForm;

    public function index()
    {
        $companyList = BsCompany::with(['seller'])->get();
        $notSettingSellerList = BsSeller::where('company_id', 0)->orderBy('updated_at', 'DESC')->get();
        return Admin::content(function (Content $content) use ($companyList, $notSettingSellerList) {
            $content->header('销售公司配置');
            $content->description('列表');
            $content->body(view('backend.settings.company', [
                'companyList' => $companyList,
                'notSettingSellerList' => $notSettingSellerList,
            ]));
        });
    }

    protected function grid()
    {
        return Admin::grid(BsStorehouse::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->created_at();
            $grid->updated_at();
        });
    }

    public function createSeller(Request $request)
    {
        Admin::js('/backend/js/company.js');
        $companyList = [0 => '请选择公司'];
        $data = $request->all();
        foreach (BsCompany::all() as $company) {
            $companyList[$company->id] = $company->name;
        }
        return Admin::content(function (Content $content) use ($data, $companyList) {
            $content->header("新增销售代表");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($data, $companyList) {
                    $form->text('sellerName', '销售员姓名');
                    $form->select('companyId', '所属公司')->options($companyList);
                    $form->setAction("createSellerSave");
                })
            );
        });
    }

    public function createSellerSave(Request $request)
    {
        $time = time();
        $request->validate([
            'sellerName' => 'required|min:1|max:50',
        ]);
        $data = $request->all();
        $sellerModel = new BsSeller();
        $sellerModel->company_id = $data['companyId'];
        $sellerModel->name = $data['sellerName'];
        $sellerModel->updated_at = $time;
        $sellerModel->created_at = $time;
        $sellerModel->save();
        return redirect('/admin/companySetting');
    }

    public function addSeller(Request $request)
    {
        $data = $request->all();
        $notSettingSellerList = [];
        foreach (BsSeller::where('company_id', 0)->get() as $seller) {
            $notSettingSellerList[$seller->id] = $seller->name;
        }
        return Admin::content(function (Content $content) use ($notSettingSellerList, $data) {
            $content->header("新增销售代表");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($notSettingSellerList, $data) {
                    $form->checkbox('sellerIdList', '请选择销售代表')->options($notSettingSellerList);
                    $form->hidden('companyId')->value($data['companyId']);
                    $form->setAction("addSellerSave");
                })
            );
        });
    }

    public function addSellerSave(Request $request)
    {
        $data = $request->all();
        BsSeller::whereIn('id', $data['sellerIdList'])->update([
            'company_id' => $data['companyId'],
        ]);
        return redirect('/admin/companySetting');
    }

    public function editSeller(Request $request)
    {
        $companyList = [0 => '请选择公司'];
        $data = $request->all();
        foreach (BsCompany::all() as $company) {
            $companyList[$company->id] = $company->name;
        }
        return Admin::content(function (Content $content) use ($data, $companyList) {
            $content->header("编辑销售代表");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($data, $companyList) {
                    $form->text('sellerName', '销售员姓名')->value($data['sellerName']);
                    $form->select('companyId', '所属公司')->options($companyList)->value(isset($data['companyId'])?$data['companyId']:'')->default(isset($data['companyName'])?$data['companyName']:'');
                    $form->hidden('sellerId')->value($data['sellerId']);
                    $form->setAction("editSellerSave");
                })
            );
        });
    }

    public function editSellerSave(Request $request)
    {
        $time = time();
        $data = $request->all();
        BsSeller::where('id', $data['sellerId'])->update([
            'name' => $data['sellerName'],
            'company_id' => $data['companyId'],
            'updated_at' => $time,
        ]);
        return redirect('/admin/companySetting');
    }

    public function sellerDestroy(Request $request)
    {
        $data = $request->all();
        $flag = BsSeller::where('id', $data['sellerId'])->delete();
        return returnJson(['flag' => $flag]);
    }

    public function companyCreate()
    {
        return Admin::content(function (Content $content) {
            $content->header("新增销售公司");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) {
                    $form->text('companyName', '公司名');
                    $form->setAction("/admin/companySetting/companyCreateSave");
                })
            );
        });
    }

    public function companyCreateSave(Request $request)
    {
        $request->validate([
            'companyName' => 'required|min:1|max:50',
        ]);
        $time = time();
        $data = $request->all();
        $companyModel = new BsCompany();
        $companyModel->name = $data['companyName'];
        $companyModel->updated_at = $time;
        $companyModel->created_at = $time;
        $companyModel->save();
        return redirect('/admin/companySetting');
    }

    public function companyEdit($companyId, $companyName)
    {
        return Admin::content(function (Content $content) use ($companyId, $companyName) {
            $content->header("修改销售公司（{$companyName}）");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($companyId, $companyName) {
                    $form->text('companyName', '公司名')->value($companyName);
                    $form->hidden('companyId')->value($companyId);
                    $form->setAction("/admin/companySetting/companyEditSave");
                })
            );
        });
    }

    public function companyEditSave(Request $request)
    {
        $request->validate([
            'companyName' => 'required|min:1|max:50',
        ]);
        $data = $request->all();
        BsCompany::where('id', $data['companyId'])->update([
            'name' => $data['companyName'],
            'updated_at' => time()

        ]);
        return redirect('/admin/companySetting');
    }

    public function companyDestroy($companyId)
    {
        DB::beginTransaction();
        try {
            BsCompany::where('id', $companyId)->delete();
            BsSeller::where('company_id', $companyId)->update([
                'company_id' => 0,
                'updated_at' => time()
            ]);
            DB::commit();
            $flag = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $flag = 0;
        }

        return returnJson(['flag' => $flag]);
    }
}
