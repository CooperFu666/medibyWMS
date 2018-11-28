<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BsPurchaseType;
use App\Models\BsStorehouse;
use App\Models\BsUsers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class PurchaseTypeSettingController extends Controller
{
    use ModelForm;

    public function index(Request $request)
    {
        $purchaseTypeList = BsPurchaseType::all();
        return Admin::content(function (Content $content) use ($purchaseTypeList) {
            $content->header('采购类型配置');
            $content->description('列表');
            $content->body(view('backend.settings.purchaseType', [
                'purchaseTypeList' => $purchaseTypeList,
            ]));
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增采购类型');
            $content->description('');
            $content->body(
                Admin::form(BsPurchaseType::class, function (Form $form) {
                    $form->text('name', '采购类型')->rules('required|regex:/^\d+$/|min:10', [
                        'regex' => 'code必须全部为数字',
                        'min'   => 'code不能少于10个字符',
                    ]);
                    $form->setAction('createSave');
                })
            );
        });
    }

    public function createSave(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:20',
        ]);
        $time = time();
        $data = $request->all();
        $purchaseTypeModel = new BsPurchaseType();
        $purchaseTypeModel->name = $data['name'];
        $purchaseTypeModel->updated_at = $time;
        $purchaseTypeModel->created_at = $time;
        $purchaseTypeModel->save();
        return redirect('/admin/purchaseTypeSetting');
    }

    public function edit($purchaseTypeId)
    {
        $purchaseType = BsPurchaseType::where('id', $purchaseTypeId)->first();
        return Admin::content(function (Content $content) use ($purchaseType) {
            $content->header('新增采购类型');
            $content->description('');
            $content->body(
                Admin::form(BsStorehouse::class, function (Form $form) use($purchaseType){
                    $form->text('name', '采购类型')->rules('required|min:3')->value($purchaseType->name);
                    $form->hidden('purchaseId', 'purchaseId')->value($purchaseType->id);
                    $form->setAction('/admin/purchaseTypeSetting/editSave');
                })
            );
        });
    }

    public function editSave(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:20',
        ]);
        $data = $request->all();
        BsPurchaseType::where('id', $data['purchaseId'])->update([
            'name' => $data['name'],
            'updated_at' => time(),
        ]);
        return redirect('/admin/purchaseTypeSetting');
    }

    public function destroy($id)
    {
        $flag = BsPurchaseType::where('id', $id)->delete();
        return returnJson(['flag' => $flag]);
    }
}
