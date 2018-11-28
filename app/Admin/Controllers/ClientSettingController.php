<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BsClient;
use App\Models\BsStorehouse;
use App\Models\BsUsers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class ClientSettingController extends Controller
{
    use ModelForm;

    public function index(Request $request)
    {
        return Admin::content(function (Content $content) {
            $content->header('客户列表');
            $content->description('');
            $content->row($this->grid());
        });
    }

    protected function grid()
    {
        return Admin::grid(BsClient::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('name', '客户名');
            $grid->column('province', '省份');
            $grid->column('city', '城市');
            $grid->column('district', '地区');
            $grid->updated_at('更新时间')->sortable()->display(function ($updatedAt) {
                return dateFormat($updatedAt);
            });
            $grid->created_at('创建时间')->sortable()->display(function ($createdAt) {
                return dateFormat($createdAt);
            });

            $grid->disableFilter();
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增客户');
            $content->description('');
            $content->body($this->form());
        });
    }

    public function createSave(Request $request)
    {
        $request->validate([
            'company' => 'required|min:1|max:50',
        ]);
        $time = time();
        $data = $request->all();
        $clientModel = new BsClient();
        $clientModel->name = $data['company'];
        $clientModel->province = $data['province'];
        $clientModel->province_id = $data['province_id'];
        $clientModel->city = $data['city'];
        $clientModel->city_id = $data['city_id'];
        $clientModel->district = $data['district'];
        $clientModel->district_id = $data['district_id'];
        $clientModel->updated_at = $time;
        $clientModel->created_at = $time;
        $clientModel->save();
        return redirect('/admin/clientSetting');
    }

    public function edit($id)
    {
        $client = BsClient::where('id', $id)->first();
        return Admin::content(function (Content $content) use($client) {
            $content->header('编辑客户');
            $content->description('');
            $content->body($this->form($client));
        });
    }

    public function editSave(Request $request, $id)
    {
        $data = $request->all();
        BsClient::where('id', $id)->update([
            'name' => $data['company'],
            'province' => $data['province'],
            'province_id' => $data['province_id'],
            'city' => $data['city'],
            'city_id' => $data['city_id'],
            'district' => $data['district'],
            'district_id' => $data['district_id'],
            'updated_at' => time(),
        ]);
        return redirect('/admin/clientSetting');
    }

    public function destroy(Request $request, $id)
    {
        $flag = 0;
        $_method = $request->post('_method');
        if ($_method == 'delete') {
            $flag = BsClient::destroy($id);
        }
        if (!empty($flag)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }

    protected function form($client = [])
    {
        if (empty($client)) {
            $client = new BsClient();
            $client->province = '请选择省份';
            $client->city = '请选择城市';
            $client->district = '请选择地区';
        }
        return Admin::form(BsUsers::class, function (Form $form) use ($client) {
            $form->text('company', '客户名')->value(!empty($client->name)?$client->name:'');
            $form->html(view('backend.settings.distpicker', ['client' => $client]), '所在地');
            $form->setAction(empty($client->id)?'createSave':"editSave");
        });
    }
}
