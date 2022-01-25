<?php

namespace App\Http\Controllers;

use App\Models\SubscribeDatatable;
use Illuminate\Http\Request;
use Inertia\Inertia;


class SubscribeDatatableController extends Controller
{
    use SubscribeDatatableCol, SubscribeDatatableAction;

    public function history_view()
    {
        return Inertia::render('History/HistoryView', [
            'tableCol' => $this->tableCol()
        ]);
    }

}

trait SubscribeDatatableCol
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
                'subscriber' => '',
                'usage' => '',
                'ip' => '',
                'time' => ''
            ]
        ];
    }

    /**
     * 下拉選單動態
     * @return array
     */
    public function SearchDynamic()
    {
        $res = SubscribeDatatable::select('subscriber')->groupBy('subscriber')->distinct()->get();
        return [
            'data' => [
                [
                    'id' => 'subscriber',
                    'data' => OptionDataMix_subscriber($res),
                ]
            ]
        ];
    }
}

trait SubscribeDatatableAction
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
                $data = SubscribeDatatable::where($query_rule)->get();
                $recordsTotal = SubscribeDatatable::where($query_rule)->count();
            } else {
                $data = SubscribeDatatable::all();
                $recordsTotal = SubscribeDatatable::count();
            }


            if (isset($data) && !empty($data)) {
                $result = array();
                foreach ($data as $key => $row) {

                    $entity = array();
                    $entity['id'] = $row['id'];
                    $entity['subscriber'] = $row['subscriber'];
                    $entity['usage'] = $row['usage'];
                    $entity['ip'] = $row['ip'];
                    $entity['time'] = $row['created_at']->format('Y/m/d H:i');

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

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {

            $res = SubscribeDatatable::create([
                'subscriber' => $request->input('subscriber'),
                'usage' => $request->input('usage'),
                'ip' => $request->input('ip'),
            ]);
            
            $ipdata = $res->get_ip;
            $ipdata['current_subscriber_id'] = $res['id'];
            $ipdata->save();

            return response()->json([
                'code' => 200,
                'msg' => 'success'
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
                $data = SubscribeDatatable::find($id);
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
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $res = SubscribeDatatable::find($id);
            $res->subscriber = $request->input('subscriber');
            $res->usage = $request->input('usage');
            $res->ip = $request->input('ip');
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

