<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\IpDatatable;

class IpDatatableController extends Controller
{
    use IpDatatableCol, IpDatatableAction;
    
    public function manage_view()
    {
        return Inertia::render('IpManage/ManageView', [
            'tableCol' => $this->tableCol()
        ]);
    }

    public function list_view(){
        return Inertia::render('IpManage/ListView', [
            'tableCol' => $this->tableCol()
        ]);
    }

}

trait IpDatatableCol
{
    /**
     * 給予欄位，可動態。
     * @return array
     */
    public function tableCol()
    {
        return [
            'data' => [
                'ip' => '',
                'gateway' => '',
                'subscriber' => '',
                'usage' => ''
            ]
        ];
    }

    /**
     * 下拉選單動態
     * @return array
     */
    public function SearchDynamic()
    {
        $res = IpDatatable::select('ip')->get();
        return [
            'data' => [
                [
                    'id' => 'ip',
                    'data' => OptionDataMix_ip($res),
                ]
            ]
        ];
    }
}

trait IpDatatableAction
{
    public function query(Request $request)
    {
        if ($request->isMethod('post')) {
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
                $data = IpDatatable::where($query_rule)->get();
                $recordsTotal = IpDatatable::where($query_rule)->count();
            } else {
                $data = IpDatatable::all();
                $recordsTotal = IpDatatable::count();
            }


            if (isset($data) && !empty($data)) {
                $result = array();
                foreach ($data as $key => $row) {
                    $entity = array();
                    $entity['ip'] = $row['ip'];
                    $entity['gateway'] = $row['gateway'];
                    $entity['subscriber'] = isset($row->get_subscribe['subscriber'])?$row->get_subscribe['subscriber']:'';
                    $entity["usage"] = isset($row->get_subscribe['usage'])?$row->get_subscribe['usage']:'';

                    $exist = array_search($entity, $result);
                    if ($exist === false) {
                        $result[] = $entity;
                    }
                }

                return response()->json([
                    "draw" => intval($draw),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => count($result),
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

    public function Ping(Request $request)
    {
        if ($request->isMethod('post')){
            $ip = $request->input('ip');
            // for cmd on windows with big5 encoding
            exec('ping ' . $ip, $ret);
            $result = [];
            foreach($ret as $r){
                array_push($result, iconv('big5', 'UTF-8', $r));
            }
            return response()->json([
                'res' => $result,
                'code' => 200,
                'msg' => 'success',
            ]);
        }
        else if ($request->isMethod('get')){
            // testing
            // for cmd on windows with big5 encoding
            exec('ping 8.8.8.8', $ret);
            $result = [];
            foreach($ret as $r){
                array_push($result, iconv('big5', 'UTF-8', $r));
            }
            dd("$result");
        }
        else {
            abort(404, 'Not Found.');
        }
    }

    /*Backdoor*/
    public function delete(Request $request)
    {
        if ($request->isMethod('post')) {
            $ip = $request->input('ip');
            if (isset($ip) && !empty($ip)) {
                $data = IpDatatable::find($ip);
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

    /*Backdoor*/
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {

            $res = IpDatatable::create([
                'ip' => $request->input('ip'),
                'gateway' => $request->input('gateway'),
            ]);

            return response()->json([
                'code' => 200,
                'msg' => 'success',
            ]);
        } else {
            abort(404, 'Not Found.');
        }
    }

    /*Backdoor*/
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $ip = $request->input('ip');
            $res = IpDatatable::find($ip);
            $res->gateway = $request->input('gateway');
            //$res->current_subscriber_id = $request->input('current_subscriber_id');
            $res->save();

            return response()->json([
                'code' => 200,
                'msg' => 'success',
            ]);
        } else {
            abort(404, 'Not Found.');
        }
    }

}
