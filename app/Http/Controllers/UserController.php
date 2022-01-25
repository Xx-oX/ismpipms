<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use UserControllerCol, UserControllerAction;

    public function user_view()
    {
        return Inertia::render('User/UserView', [
            'tableCol' => $this->tableCol()
        ]);
    }

}

trait UserControllerCol
{
    /**
     * 給予欄位，可動態。
     * @return array
     */
    public function tableCol()
    {
        return [
            'data' => [
                'id' => '',
                'name' => '',
                'email' => '',
                'role' => '',
                'button' => ''
            ]
        ];
    }

    /**
     * 下拉選單動態
     * @return array
     */
    public function SearchDynamic()
    {
        $res = User::select('name')->get();
        return [
            'data' => [
                [
                    'id' => 'name',
                    'data' => OptionDataMix_name($res),
                ]
            ]
        ];
    }
}

trait UserControllerAction
{
    public function query(Request $request)
    {
        if (true || $request->isMethod('post')) {
            $query = $request->input('query');
            $draw = $request->input('draw');
            $rows = $request->input('length');
            $offset = $request->input('start');


            if (isset($query) && !empty($query)) {
                $query_rule = array();
                foreach ($query as $key => $row) {
                    if ($row['value'] != null) {
                        $query_rule[] = [$row['key'], 'like', '%' . $row['value'] . '%'];
                    }
                }
            }

            if (isset($query_rule) && count($query_rule) > 0) {
                $data = User::where($query_rule)->get();
                $recordsTotal = User::where($query_rule)->count();
            } else {
                $data = User::all();
                $recordsTotal = User::count();
            }


            if (isset($data) && !empty($data)) {
                $result = array();
                foreach ($data as $key => $row) {

                    $entity = array();
                    $entity['id'] = $row['id'];
                    $entity['name'] = $row['name'];
                    $entity['email'] = $row['email'];
                    $entity['role'] = $row->roles->pluck('name');
                    $entity['button'] = array('修改');

                    $exist = array_search($entity, $result);
                    if ($exist === false) {
                        $result[] = $entity;
                    }
                }

                return response()->json([
                    'draw' => intval($draw),
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => count($result),
                    'data' => array_slice($result, $offset, $rows),
                ]);
            }
            return response()->json([
                'code' => 500,
                'msg' => '系統錯誤請通知管理員',
            ]);
        } else {
            abort(404, 'Not Found.');
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $uid = Auth::user()->id;
            $user = User::find($uid);
            $id = $request->input('id');
            $res = User::find($id);
            if($res->can('manage manager')){
                return response()->json([
                    'code' => 403,
                    'msg' => 'permission denial',
                ]);
            }
            if($user->id == $res->id){
                return response()->json([
                    'code' => 403,
                    'msg' => $res->roles->pluck('name'),
                ]);
            }
            if($user->can('manage users')){
                if($res->can('manage users')){
                    if(!$user->can('manage manager')){
                        return response()->json([
                            'code' => 403,
                            'msg' => 'permission denial',
                        ]);
                    }
                }
                $res->name = $request->input('name');
                $res->email = $request->input('email');
                $res->roles()->detach();
                $res->assignRole($request->input('role'));
                $res->save();
            }
            else{
                return response()->json([
                    'code' => 403,
                    'msg' => 'permission denial',
                ]);
            }
            // useless
            return response()->json([
                'code' => 200,
                'msg' => 'success',
            ]);
        } else {
            abort(404, 'Not Found.');
        }
    }

    /*Backdoor*/
    public function delete(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            if (isset($id) && !empty($id)) {
                $data = User::find($id);
                if ($data) {
                    $res = $data->delete();

                    return response()->json([
                        'code' => 200,
                        'msg' => 'success',
                    ]);
                } else {
                    return response()->json([
                        'code' => 200,
                        'msg' => '資料不存在',
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 500,
                    'msg' => '請選擇欲刪除之資料',
                ]);
            }
        } else {
            abort(404, 'Not Found.');
        }
    }
}

