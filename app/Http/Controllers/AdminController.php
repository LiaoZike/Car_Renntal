<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminAuthController;
use App\Models\Admin_Rental;
use App\Models\Admin_Insurance;
use App\Models\Admin_Location;
use App\Models\Admin_Model;
use App\Models\Admin_Member;
use App\Models\Insurance;
use App\Models\Rental;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AdminController{
    protected $carTypeMapping = [
        'Compact' => '小型車',
        'Sedan' => '中大型房車',
        'SUV' => 'SUV',
        'MPV' => 'MPV',
    ];
    protected $fuelTypeMapping = [
        'Gasoline' => '汽油',
        'Hybrid' => '油電混合',
        'Electric' => '電動',
    ];
    protected function info_replace_chinese($orders){
        if(!empty($orders)){
            foreach ($orders as &$order) {
                $order->car_type = $this->carTypeMapping[$order->car_type] ?? $order->car_type;
                $order->fuel_type = $this->fuelTypeMapping[$order->fuel_type] ?? $order->fuel_type;
                $order->transmission = ($order->transmission)?"自排":"手排";
            }
        }
        return $orders;
    }

    #用於controller判斷是否有權限，若無跳回登入介面
    private function controller_auth_check(){
        $controller = new AdminAuthController();
        return $controller->verification();
    }
    public function login_page(){
        if(is_null(session('admin_free_retry')) || session('admin_free_retry')===true){
            session(['admin_free_retry'=>false]);
            $controller = new AdminAuthController();
            if($controller->verification()) return redirect()->route('admin.home');
        }
        return view('admin.login');
    }

    public function login_auth(Request $request){
        $controller = new AdminAuthController();
        $return_data=$controller->login($request);
        if ($return_data!="OK"){
            return redirect()->route('admin.login.page')
                ->withErrors(['account' => $return_data])
                ->withInput($request->only('username'));
        }
        session(['admin_retry' => False]);
        return redirect()->route('admin.home');
    }
    //首頁
    public function home(){
        $statusCounts = Admin_Rental::count_rental_status();
        $expried_datas = Admin_Rental::search_Expired_order();
        return view('admin.home', [
            'counts' => $statusCounts,
            'total'  => $statusCounts['total'],
            'expried_datas'=>$expried_datas
        ]);
    }
    public function rental($status){
        //取得基礎資料
        $rentalOrders = Admin_Rental::search_order();
        $rentalOrders=$this->info_replace_chinese($rentalOrders);
        $now = now();
        foreach ($rentalOrders as $order) {
            if ($order->rental_status === 'active') {
                if ($order->start_date > $now) {
                    $order->rental_status = 'active_not_started';
                } elseif ($order->start_date <= $now && $order->end_date >= $now) {
                    $order->rental_status = 'active_ongoing';
                }
            }
        }

        //計算機額顯示
        if(!empty($rentalOrders)){
            foreach($rentalOrders as $order){
                $pickupDateTime=Carbon::parse($order->start_date); $returnDateTime=Carbon::parse($order->end_date);
                $diffInHours = $pickupDateTime->diffInHours($returnDateTime);
                $days = intdiv($diffInHours, 24);
                $remainHours = $diffInHours % 24;
                if ($remainHours < 1) {
                    $total_days = $days;
                } else {
                    $total_days = $days + 1;
                }
                $total_days=max($total_days,1);
                $show_account_calc = "{$total_days}天 × (車輛費{$order->daily_fee} + 保險費{$order->ins_fee}) = ".(int)$order->total_cost." 元";
                $order->total_days=$total_days;
                $order->show_account_calc=$show_account_calc;
            }
        }

        $insurances=Insurance::search_all();

        $datas = [
            'rentalOrders' => $rentalOrders,
            'status' => $status,
            'insurances'=>$insurances,
        ];
        return view('admin.Rental', $datas);
    }
    public function rental_update(Request $request){
        $validated = $request->validate([
            'order_id' => 'required',
            'insurance_id' => 'required',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'order_status' => 'required',
            'changes' => 'required',
        ]);
        $order_id = $validated['order_id'];
        $insurance_id = $validated['insurance_id'];
        $payment_method = $validated['payment_method'];
        $payment_status = $validated['payment_status'];
        $order_status = $validated['order_status'];
        if($order_status=="cancelled" || Admin_Rental::search_order_status($order_id)=="cancelled"){
            return response()->json([
                'status' => 'error',
                'message' => '不可變更狀態:顧客取消'
            ]);
        }

        //
        //Stage6: 預先計算費用
        /******************************** 計算總額 *******************************/
        $rental_data=Rental::search_order_data($order_id);
        $pickupDateTime=Carbon::parse($rental_data->start_date); $returnDateTime=Carbon::parse($rental_data->end_date);
        $diffInHours = $pickupDateTime->diffInHours($returnDateTime);
        $days = intdiv($diffInHours, 24);
        $remainHours = $diffInHours % 24;
        if ($remainHours < 1) {
            $total_days = $days;
        } else {
            $total_days = $days + 1;
        }
        $total_days=max($total_days,1);
        $carfee = Rental::search_car_fee($rental_data->car_id);
        $insurancefee = Rental::search_insurance_fee($insurance_id);
        $total_amount = $total_days * ($carfee + $insurancefee);

        //Stage: 更新訂單動作
        $changes = $validated['changes'];
        Admin_Rental::update_order($order_id,$insurance_id,$payment_method,$payment_status,$order_status,$total_amount);
        session(['web_status'=>'success']);
        session(['web_status_description' => '修改成功']);
        return response()->json([
            'status' => 'success',
            'message' => '修改成功'
        ]);

    }


    //各DB資訊顯示功能
    public function storeInfo() {
        $locations = Admin_Location::search_all();
        $datas=[
            'locations' => $locations,
        ];
        return view('admin.store_info',$datas);
    }

    public function memberInfo() {
        return view('admin.member_info');
    }

    public function carManagement() {
        $results=Admin_Model::search_all();
        $locations = Admin_Location::search_all();
        // 將結果分組，按車型分配車輛
        $groupedResults = [];
        foreach ($results as $row) {
            $modelId = $row->model_id;
            if (!isset($groupedResults[$modelId])) {
                $groupedResults[$modelId] = [
                    'model_id' => $row->model_id,
                    'brand' => $row->brand,
                    'model_name' => $row->model_name,
                    'car_type' => $row->car_type,
                    'fuel_type' => $row->fuel_type,
                    'engine_cc' => $row->engine_cc,
                    'transmission' => $row->transmission,
                    'image_url' => $row->image_url,
                    'cars' => [], // 初始化車輛列表
                ];
            }
            if ($row->car_id) {
                $groupedResults[$modelId]['cars'][] = [
                    'car_id' => $row->car_id,
                    'vin' => $row->vin,
                    'plate_number' => $row->plate_number,
                    'daily_fee' => $row->daily_fee,
                    'late_fee' => $row->late_fee,
                    'year_made' => $row->year_made,
                    'seat_num' => $row->seat_num,
                    'color' => $row->color,
                    'mileage' => $row->mileage,
                    'car_status' => $row->car_status,
                    'notes' => $row->notes,
                ];
            }
        }
        $datas=[
            'models'=>$groupedResults,
            'locations' => $locations
        ];
        return view('admin.car_management',$datas);
    }


    /*********************************************/
    /* Block:保險編輯功能                         */
    /*********************************************/
    public function insuranceManagement() {
        $insurances=Admin_Insurance::search_all();
        $datas=[
           'insurances'=>$insurances,
        ];
        return view('admin.insurance_management',$datas);
    }
    public function editInsurance($id){
        $insurance = Admin_Insurance::find_ins($id);

        if (!$insurance) {
            return response()->json(['error' => '保險資料不存在'], 404);
        }
        return response()->json($insurance);
    }

    public function updateInsurance(Request $request){
        $validated = $request->validate([
            'insurance_id' => 'required',
            'ins_name' => 'required|string|max:255',
            'ins_fee' => 'required|numeric|min:0',
            'coverage' => 'required|string',
        ]);

        // 獲取當前資料庫中的保險資料
        $currentInsurance = Admin_Insurance::find_ins($validated['insurance_id']);

        if (!$currentInsurance) {
            return response()->json(['error' => '保險資料不存在'], 404);
        }

        // 檢查是否有變更
        $hasChanges = false;
        foreach (['ins_name', 'ins_fee', 'coverage'] as $field) {
            if ($currentInsurance->$field != $validated[$field]) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return response()->json(['message' => '未檢測到任何變更。'], 200);
        }

        // 檢查是否有變更
        $updateResult = Admin_Insurance::updateDBInsurance($validated);

        if ($updateResult) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '保險資料更新成功']);
            return response()->json(['success' => '保險資料更新成功']);
        } else {
            return response()->json(['error' => '更新失敗，請稍後再試。'], 500);
        }
    }
    public function addInsurance(Request $request){
        $validated = $request->validate([
            'ins_name' => 'required|string|max:255',
            'ins_fee' => 'required|numeric|min:0',
            'coverage' => 'required|string',
        ]);

        $result = Admin_Insurance::addInsurance($validated);

        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '保險資料更新成功']);
            return response()->json(['success' => '保險方案新增成功']);
        } else {
            return response()->json(['error' => '新增失敗，請稍後再試'], 500);
        }
    }

    public function deleteInsurance($id){
        try {
            $result = Admin_Insurance::deleteInsurance($id);
            if ($result) {
                session(['web_status' => 'success']);
                session(['web_status_description' => '保險方案刪除成功']);
                return response()->json(['success' => '保險方案刪除成功']);
            } else {
                return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // 檢查是否是外鍵約束錯誤
            if ($e->getCode() == 23000) { // SQLSTATE[23000] 是外鍵約束錯誤代碼
                return response()->json([
                    'error' => '無法刪除該保險方案，因為它已被其他資料引用。'
                ], 400);
            }

            // 其他錯誤
            return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
        }
    }
    /*********************************************/
    /*********************************************/


    /*********************************************/
    /* Block:地點編輯功能                         */
    /*********************************************/
    public function addLocation(Request $request){
        $validated = $request->validate([
            'loc_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $result = Admin_Location::addLocation($validated);
        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '地點新增成功']);
            return response()->json(['success' => '地點新增成功']);
        } else {
            return response()->json(['error' => '新增失敗，請稍後再試'], 500);
        }
    }

    public function editLocation($id){
        $location = Admin_Location::findLocation($id);
        if (!$location) {
            return response()->json(['error' => '地點資料不存在'], 404);
        }

        return response()->json($location);
    }

    public function updateLocation(Request $request){
        $validated = $request->validate([
            'loc_id' => 'required',
            'loc_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $result = Admin_Location::updateLocation($validated);

        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '地點資料更新成功']);
            return response()->json(['success' => '地點資料更新成功']);
        } else {
            return response()->json(['error' => '更新失敗，請稍後再試'], 500);
        }
    }

    public function deleteLocation($id){
        try {
            $result = Admin_Location::deleteLocation($id);
            if ($result) {
                session(['web_status' => 'success']);
                session(['web_status_description' => '地點刪除成功']);
                return response()->json(['success' => '地點刪除成功']);
            } else {
                return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // 檢查是否是外鍵約束錯誤
            if ($e->getCode() == 23000) { // SQLSTATE[23000] 是外鍵約束錯誤代碼
                return response()->json([
                    'error' => '無法刪除該地點，因為它已被其他資料引用。'
                ], 400);
            }

            // 其他錯誤
            return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
        }
    }
    /*********************************************/
    /*********************************************/

    /*********************************************/
    /* Block:車型編輯功能                         */
    /*********************************************/
    public function addModel(Request $request) {
        $validated = $request->validate([
            'brand' => 'required|string|max:30',
            'model_name' => 'required|string|max:30',
            'car_type' => 'required|in:Compact,Sedan,SUV,MPV',
            'fuel_type' => 'required|in:Gasoline,Electric,Hybrid',
            'engine_cc' => 'required|integer|min:0',
            'transmission' => 'required|boolean',
            'image_url' => 'required',
        ]);
        $result = Admin_Model::addModel($validated);
        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '車型新增成功']);
            return response()->json(['success' => '車型新增成功']);
        } else {
            return response()->json(['error' => '新增失敗，請稍後再試'], 500);
        }
    }
    //刪除車型
    public function deleteModel($id) {
        try {
            $result = Admin_Model::deleteModel($id);
            if ($result) {
                session(['web_status' => 'success']);
                session(['web_status_description' => '車型刪除成功']);
                return response()->json(['success' => '車型刪除成功']);
            } else {
                return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // SQLSTATE[23000] 是外鍵約束錯誤代碼
                return response()->json([
                    'error' => '無法刪除該車型，因為它已被其他資料引用。'
                ], 400);
            }

            // 其他錯誤
            return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
        }
    }
    //編輯車型
    public function editModel($id) {

        $model = Admin_Model::find_Model($id);
        if (!$model) {
            return response()->json(['error' => '車型不存在'], 404);
        }
        return response()->json($model);
    }
    //更新車型
    public function updateModel(Request $request) {
        $validated = $request->validate([
            'model_id' => 'required',
            'brand' => 'required|string|max:30',
            'model_name' => 'required|string|max:30',
            'car_type' => 'required|in:Compact,Sedan,SUV,MPV',
            'fuel_type' => 'required|in:Gasoline,Electric,Hybrid',
            'engine_cc' => 'required|integer|min:0',
            'transmission' => 'required|boolean',
            'image_url' => 'required',
        ]);
        $result = Admin_Model::updateModel($validated);
        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '車型資料更新成功']);
            return response()->json(['success' => '車型資料更新成功']);
        } else {
            return response()->json(['error' => '更新失敗，請稍後再試'], 500);
        }
    }
    /*********************************************/
    /*********************************************/

    /*********************************************/
    /* Block:汽車編輯功能                         */
    /*********************************************/
    public function addCar(Request $request) {
        $validated = $request->validate([
            'model_id' => 'required',
            'plate_number' => 'required|string|max:10',
            'vin' => 'required|string|max:50',
            'daily_fee' => 'required|integer|min:0',
            'loc_id' => 'required|integer',
            'late_fee' => 'required|integer|min:0',
            'year_made' => 'required|integer|min:1980',
            'seat_num' => 'required|integer|min:1',
            'color' => 'required|string|max:20',
            'mileage' => 'required|integer|min:0',
            'car_status' => 'required|in:available,maintenance,disable',
            'notes' => 'nullable|string',
        ]);

        try {
            $result = Admin_Model::addCar($validated);
            if ($result) {
                session(['web_status' => 'success']);
                session(['web_status_description' => '汽車新增成功']);
                return response()->json(['success' => '汽車新增成功']);
            } else {
                return response()->json(['error' => '新增失敗，請稍後再試'], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // 檢查是否是外鍵約束錯誤
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => '車牌號碼格式必須RXX-dddd。'
                ], 400);
            }

            // 其他錯誤
            return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
        }
    }
    public function deleteCar($id) {
        try {
            $result = Admin_Model::deleteCar($id);
            if ($result) {
                session(['web_status' => 'success']);
                session(['web_status_description' => '汽車刪除成功']);
                return response()->json(['success' => '汽車刪除成功']);
            } else {
                return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // SQLSTATE[23000] 是外鍵約束錯誤代碼
                return response()->json([
                    'error' => '無法刪除該汽車，因為它已被其他資料引用。'
                ], 400);
            }
            // 其他錯誤
            return response()->json(['error' => '刪除失敗，請稍後再試'], 500);
        }
    }

    public function editCar($id) {
        $car = Admin_Model::find_car($id);
        if (!$car) {
            return response()->json(['error' => '汽車資料不存在'], 404);
        }
        return response()->json($car);
    }


    public function updateCar(Request $request) {
        $validated = $request->validate([
            'car_id' => 'required',
            'plate_number' => 'required|string|max:8',
            'vin' => 'required',
            'loc_id' => 'required',
            'daily_fee' => 'required|integer|min:0',
            'late_fee' => 'required|integer|min:0',
            'year_made' => 'required|integer|min:1980',
            'seat_num' => 'required|integer|min:1',
            'color' => 'required|string|max:20',
            'mileage' => 'required|integer|min:0',
            'car_status' => 'required|in:available,maintenance,disable',
            'notes' => 'nullable|string',
        ]);
        $result = Admin_Model::updateCar($validated);
        if ($result) {
            session(['web_status' => 'success']);
            session(['web_status_description' => '車型資料更新成功']);
            return response()->json(['success' => '車型資料更新成功']);
        } else {
            return response()->json(['error' => '更新失敗，請稍後再試'], 500);
        }
    }
    /*********************************************/
    /*********************************************/

    public function showMembers() {
        $members = Admin_Member::search_all();
        $datas = [
            'members' => $members,
        ];
        return view('admin.member_info', $datas);
    }

    public function pictureManagement(){
        $imageDirectory = public_path('img'); // 指向 public/images 資料夾
        $images = [];

        if (file_exists($imageDirectory)) {
            $files = scandir($imageDirectory); // 獲取資料夾中的所有檔案
            foreach ($files as $file) {
                if (is_file($imageDirectory . '/' . $file)) {
                    $images[] = asset('img/' . $file); // 生成圖片的完整 URL
                }
            }
        }
        return view('admin.image_list', compact('images'));
    }
    public function uploadImage(Request $request) {
        // 預設為錯誤狀態
        session(['web_status' => 'error']);
        session(['web_status_description' => '上傳檔案格式不符合']);

        // 驗證圖片格式和大小
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif', // 最大 2MB
        ]);

        // 獲取圖片檔案
        $image = $request->file('image');
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // 原始檔名（不含副檔名）
        $extension = $image->getClientOriginalExtension(); // 副檔名
        $imageDirectory = public_path('img'); // 圖片存放目錄

        // 確保檔名唯一
        $imageName = $originalName . '.' . $extension;
        $counter = 1;
        while (file_exists($imageDirectory . '/' . $imageName)) {
            $imageName = $originalName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        // 將圖片移動到 public/images/model 資料夾
        $image->move($imageDirectory, $imageName);

        // 更新狀態為成功
        session(['web_status' => 'success']);
        session(['web_status_description' => '上傳圖片成功']);


        // 重定向到圖片管理頁面
        return redirect()->route('admin.pictureManagement');
    }
    public function deleteImage(Request $request) {
        $validated = $request->validate([
            'image_path' => 'required|string', // 驗證圖片路徑
        ]);
        if (!str_starts_with($validated['image_path'], 'img/')) {
            session(['web_status_description' => '無效的圖片路徑']);
            return response()->json(['error' => '無效的圖片路徑'], 400);
        }
        $imagePath = public_path($validated['image_path']); // 獲取完整路徑
        if (file_exists($imagePath)) {
            unlink($imagePath); // 刪除圖片
            session(['web_status' => 'success']);
            session(['web_status_description' => '圖片刪除成功']);
            return response()->json(['success' => '圖片刪除成功']);
        } else {
            session(['web_status' => 'error']);
            session(['web_status_description' => '圖片不存在或已被刪除']);
            return response()->json(['error' => '圖片不存在或已被刪除'], 404);
        }
    }
}
