<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BsStorehouse;
use App\Models\BsUsers;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class StorehouseSettingController extends Controller
{
    use ModelForm;

    public function index(Request $request)
    {
        $storehouseList = BsStorehouse::with(['users'])->get();
        $notSettingUserList = BsUsers::where('storehouse_id', 0)->get();
        return Admin::content(function (Content $content) use ($storehouseList, $notSettingUserList) {
            $content->header('库房配置');
            $content->description('列表');
            $content->body(view('backend.settings.storehouse', [
                'storehouseList' => $storehouseList,
                'notSettingUserList' => $notSettingUserList
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

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增库房');
            $content->description('');
            $content->body($this->form());
        });
    }

    public function edit(Request $request)
    {
        return Admin::content(function (Content $content) use ($request) {
            $content->header('编辑库房');
            $content->description('');
            $content->body($this->form($request->get('id')));
        });
    }

    public function destroy(Request $request)
    {
        $time = time();
        $storeHouseId = $request->post('storehouseId');
        DB::beginTransaction();
        try {
            BsStorehouse::where('id', $storeHouseId)->delete();
            BsUsers::where('storehouse_id', $storeHouseId)->update([
                'storehouse_id' => 0,
                'updated_at' => $time
            ]);
            DB::commit();
            $flag = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $flag = 0;
        }
        return returnJson(['flag' => $flag]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:20',
        ]);
        $time = time();
        $data = $request->all();
        if (!empty($data['id'])) {  //编辑
            BsStorehouse::where('id', $data['id'])->update([
                'name' => $data['name'],
                'updated_at' => $time,
            ]);
        } else {    //新增
            $storehouseModel = new BsStorehouse();
            $storehouseModel->name = $data['name'];
            $storehouseModel->created_at = $time;
            $storehouseModel->updated_at = $time;
            $storehouseModel->save();
        }
        return redirect('/admin/storehouseSetting/');
    }

    public function userEdit(Request $request)
    {
        $storehouseIdSelectedList = [0 => '不配置库房'];
        $data = $request->all();
        $user = BsUsers::where('id', $data['userId'])->first();
        $storehouseList = BsStorehouse::all();
        foreach ($storehouseList as $storehouse) {
            $storehouseIdSelectedList[$storehouse->id] = $storehouse->name;
        }
        return Admin::content(function (Content $content) use ($user, $storehouseIdSelectedList, $data) {
            $content->header('用户编辑');
            $content->description('手机号码编辑');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($user, $storehouseIdSelectedList, $data) {
                    $form->text('phone', '手机号码')->value($user->phone);;
                    $form->select('storehouseId', '请选择库房')->options($storehouseIdSelectedList)->value($data['storehouseId'])->default($data['storehouseName']);
                $form->setAction('userSave?userId=' . $user->id);
                })
            );
        });
    }

    public function userSave(Request $request)
    {
        $request->validate([
            'phone' => 'required|min:1111111|max:99999999999|numeric',
        ]);
        $data = $request->all();
        BsUsers::where('id', $data['userId'])->update([
            'storehouse_id' => $data['storehouseId'],
            'phone' => $data['phone'],
            'updated_at' => time(),
        ]);
        return redirect('/admin/storehouseSetting');
    }

    public function isFreeze(Request $request)
    {
        $data = $request->all('userId', 'isFreeze');
        $status = BsUsers::FREEZE;
        if ($data['isFreeze'] == BsUsers::FROZEN) {
            $status = BsUsers::FROZEN;
        }
        BsUsers::where('id', $data['userId'])->update([
            'is_freeze' => $status,
            'updated_at' => time(),
        ]);
        return returnJson(['flag' => 1]);
    }

    public function resetPassword(Request $request)
    {
        $userId = $request->get('userId');
        $flag = BsUsers::where('id', $userId)->update([
            'password' => config('admin.params.default_password'),
            'updated_at' => time(),
        ]);
        return returnJson(['flag' => $flag]);
    }

    public function addUserToStoreHouse(Request $request)
    {
        $userIdCheckboxList = [];
        $data = $request->all();
        $userList = BsUsers::where('storehouse_id', 0)->get();
        foreach ($userList as $user) {
            $userIdCheckboxList[$user->id] = "{$user->nickname}（{$user->username}）";
        }
        return Admin::content(function (Content $content) use ($userIdCheckboxList, $data) {
            $content->header("添加工作人员到{$data['storehouseName']}");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use ($userIdCheckboxList, $data) {
                    $form->checkbox('userIdList', '选择工作人员')->options($userIdCheckboxList);
                    $form->hidden('storehouseId')->value($data['storehouseId']);
                    $form->setAction('addUserToStoreHouseSave');
                })
            );
        });
    }

    public function addUserToStoreHouseSave(Request $request)
    {
        $data = $request->all();
        BsUsers::whereIn('id', $data['userIdList'])->update([
            'storehouse_id' => $data['storehouseId'],
            'updated_at' => time(),
        ]);
        return redirect('/admin/storehouseSetting');
    }

    public function createUser()
    {
        $storehouseIdSelectedList = [0 => '请选择库房'];
        $storehouseList = BsStorehouse::all();
        foreach ($storehouseList as $storehouse) {
            $storehouseIdSelectedList[$storehouse->id] = $storehouse->name;
        }
        return Admin::content(function (Content $content) use($storehouseIdSelectedList) {
            $content->header("新建工作人员");
            $content->description('');
            $content->body(
                Admin::form(BsUsers::class, function (Form $form) use($storehouseIdSelectedList) {
                    $form->text('phone', '联系电话');
                    $form->select('storehouseId', '请选择库房')->options($storehouseIdSelectedList)->value(0)->default('不配置库房');
                    $form->text('nickname', '姓名');
                    $form->text('username', '登录名');
                    $form->setAction('createUserSave');
                })
            );
        });
    }

    public function createUserSave(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'nickname' => 'required|min:1|max:20',
            'username' => 'required|min:1|max:20',
        ]);
        $time = time();
        $data = $request->all();
        BsUsers::insert([
            'username' => $data['username'],
            'password' => config('admin.params.default_password'),
            'storehouse_id' => $data['storehouseId'],
            'nickname' => $data['nickname'],
            'phone' => $data['phone'],
            'updated_at' => $time,
            'created_at' => $time,
        ]);
        return redirect('/admin/storehouseSetting');
    }

    protected function form($id = null)
    {
        $storehouse = [];
        if (!empty($id)) {
            $storehouse = BsStorehouse::find($id);
        }
        return Admin::form(BsStorehouse::class, function (Form $form) use ($storehouse) {
            if (!empty($storehouse)) {
                $form->text('name', '库房名')->value($storehouse->name);
                $form->setAction('store?id=' . $storehouse->id);
            } else {
                $form->text('name', '库房名');
                $form->setAction('store');
            }
        });
    }
}
