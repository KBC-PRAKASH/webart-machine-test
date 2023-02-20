<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Children,
    ChildrenPickedUp,
    Country,
    State,
    City
};
use Illuminate\Http\Request;

class ChildrenController extends Controller
{
    public function index()
    {
        $results = [];
        $results['page_title'] = "Home page";
        $results['countries']  = Country::select('id','name')->where('status', 1)->get();
        return view('children.index', $results);
    }

    public function getOptionValues(Request $request) {
        $results = ['status' => false, 'message' => "Oops! Something went wrong"];
        try{
            if(isset($request->selected_attr) && !is_null($request->selected_attr)) {
                if($request->selected_attr == "country_id" && !empty($request->country_id)) {
                    $stateList = State::select('id','name')->where(['country_id' =>  $request->country_id, 'status' => 1])->orderBy('name')->get();
                    if(count($stateList) > 0) {
                        $results['status'] = true;
                        $results['data']   = $stateList;
                        $results['message'] = "Records found successfully";
                    } else {
                        $results['message'] = "State not found";
                    }
                }else if($request->selected_attr == "state_id" && !empty($request->state_id)) {
                    $cityList = City::select('id','name')->where(['state_id' =>  $request->state_id, 'status' => 1])->orderBy('name')->get();
                    if(count($cityList) > 0) {
                        $results['status'] = true;
                        $results['data']   = $cityList;
                        $results['message'] = "Records found successfully";
                    } else {
                        $results['message'] = "State not found";
                    }
                } else {
                    $results['message'] = "Record not found";
                }
            } else {
                $results['message'] = " Sorry, an error occurred while processing your request";
            }
        } catch(\Exception $e) {
            $results['message'] = $e->getMessage();
        }
        return response()->json($results);
    }

    public function handleRegistration(Request $request) {
        $status = array('success' => false, 'message' => 'Oops! something went wrong');
        DB::beginTransaction();
        try {
            $insertData = array_map('trim', $request->except(['_token', 'person_name', 'relationship', 'phone'])); //TRIM SPACES FROM INPUT
            $validator = Validator::make($insertData, Children::$rules);
            if ($validator->fails()) {
                $status['message'] = 'Please enter all mandatory fields';
                return $status;
            }
            
            $personNames = array_count_values($request->person_name);
            if (count($personNames) > 0) {
                foreach ($personNames as $key => $personName) {
                    if($personName > 1) {
                        $status['message'] = "You can't enter the same person name";
                        return response()->json($status);
                    }
                }
            }

            $relationships = array_count_values($request->relationship);
            if (count($relationships) > 0) {
                foreach ($relationships as $key => $relationship) {
                    if($relationship > 1) {
                        $status['message'] = "You can't select the same picked-up detail";
                        return response()->json($status);
                    }
                }
            }

            $phones = array_count_values($request->phone);
            if (count($phones) > 0) {
                foreach ($phones as $key => $phone) {
                    if($phone > 1) {
                        $status['message'] = "You can't enter the same phone";
                        return response()->json($status);
                    }
                }
            }
            $insertData['date_of_birth']    = date('Y-m-d', strtotime($request->date_of_birth));
            $insertData['created_at']       = date('Y-m-d H:i:s');
            if ($request->hasFile('photo')) {
                $getimageName = 'user_' . md5(rand()).'.'.$request->photo->getClientOriginalExtension();
                $request->photo->storeAs('public/photos/', $getimageName);
                $insertData['photo']     = $getimageName;
            }

            $childrenId = Children::insertGetId($insertData);
            

            $relationshipData = [];
            if(isset($childrenId) && !empty($childrenId)) {
                foreach ($request->relationship as $i => $relationship) {
                    $relationshipData[] = [
                        'children_id'   => $childrenId,
                        'name'          => $request->person_name[$i],  
                        'relationship'  => $relationship,  
                        'phone'         => $request->phone[$i],
                        'created_at'    => date('Y-m-d H:i:s')
                    ];
                }
            }

            $result = ChildrenPickedUp::insert($relationshipData);
            if(!$result) {
                return $status;
            }

            $status['success'] = true;
            $status['message'] = 'Congratulations! Your registration form successfully submitted';
            DB::commit();
            return $status;
        }catch(\Exception $e) {
            $status['message'] = $e->getMessage();
            DB::rollback();
            return $status;
        }
    }

    public function getRegistrationData(Request $request) {
        $results = ['status' => false, 'message' => "Oops! Something went wrong"];
        try{
            $childrenList = Children::with('country', 'state', 'city', 'details')->orderBy('created_at')->get();
            if(count($childrenList) > 0) {
                $results['status'] = true;
                $results['data']   = $childrenList;
                $results['message'] = "Records found successfully";
            } else {
                $results['message'] = "Records not found";
            }
        } catch(\Exception $e) {
            $results['message'] = $e->getMessage();
        }
        return response()->json($results);
    }
}
