<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private UserRepository $UserRepository,private RoleRepository $RoleRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError('Unauthorized', 403);
            }
            $Users=$this->UserRepository->index();
            return ApiResponseClass::sendResponse($Users, 'All Users retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Users: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $User = $this->UserRepository->getById($id);
            return ApiResponseClass::sendResponse($User, " data getted successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned User: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        if($id != Auth::id()){
            return ApiResponseClass::sendError('غير مسموح لك بتحديث بيانات هذا المستخدم');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes','string','max:100'],
            'phone_number' => ['sometimes','string','min:10','max:15',],
            'receive_site_notifications'=> ['sometimes','boolean'],
            'receive_email_notifications'=> ['sometimes','boolean']
        ],[
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 100 حرف.',
            'phone_number.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone_number.min' => 'يجب أن يتكون رقم الهاتف من 10 أرقام على الأقل.',
            'phone_number.max' => 'يجب ألا يتجاوز رقم الهاتف 15 رقمًا.',
            'receive_site_notifications.boolean'=>'يجب ان يكون الاشعارات عبر الموقع اما 1 مقبول او 0 غير مقبول',
            'receive_email_notifications.boolean'=>'يجب أن يكون الاشعارات عبر البريد الالكتروني إما 1 مقبول او 0 غير مقبول',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['name','phone_number','receive_email_notifications','receive_site_notifications']);
            $User=$this->UserRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($User,'User is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated User: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $User=$this->UserRepository->getById($id);
            if($id == Auth::id()){
                if($this->UserRepository->delete($User->id)){
                    return ApiResponseClass::sendResponse($User, "{$User->id} unsaved successfully.");
                }
            }
            else{
                return ApiResponseClass::sendError('You do not have permission to delete this user.');
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting User: ' . $e->getMessage());
        }
    }

    public function changePassword(Request $request,$id){
        if($id != Auth::id()){
            return ApiResponseClass::sendError('You do not have permission to changePassword this user.');
        }
        $validator=Validator::make($request->all(),[
            'old_password'=>['required'],
            'new_password'=>['required', 'string', 'min:8','confirmed'],
        ],[
            'old_password.required'=>'يجب كتابة كلمة المرور القديمة',
            'new_password.required'=>'يجب كتابة كلمة المرور الجديده',
            'new_password.confirmed' => 'يجب تأكيد كلمة المرور الجديدة',
            'new_password.min' => 'يجب أن تتكون كلمة المرور الجديدة من 8 أحرف على الأقل',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $user=$this->UserRepository->getById(Auth::id());
            $fields=$request->only(['old_password','new_password']);
            $result=$this->UserRepository->changePassword($fields,$user);
            if($result){
                $user->tokens()->delete(); 
                return ApiResponseClass::sendResponse(null, "تم تغيير كلمة المرور الخاصة بـ {$user->name}. يرجى تسجيل الدخول مرة أخرى.");
            }
            return ApiResponseClass::sendError('كلمة المرور غير صحيحة');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error change Password: ' . $e->getMessage());
        }
        
    }

    public function assignRole(Request $request, $user_id){
        if (!Auth::user()->has_role('admin')) {
            return ApiResponseClass::sendError('ليس لديك صلاحية لاعطاء دور ل مستخدم', 403);
        }
        $validator = Validator::make($request->all(), [
            'role' => ['required','string',Rule::exists('roles','name')]
        ],[
            'role.required' => 'يجب كتابة اسم الدور',
            'role.exists' => 'اسم الدور غير موجود في النظام',
            'role.string' => 'يجب أن يكون أسم الدور نصاً',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['role']);
            $user=$this->UserRepository->getById($user_id);
            $role=$this->RoleRepository->getByName($fields['role']);
            $role=$this->UserRepository->assignRole($user,$role);
            return ApiResponseClass::sendResponse(['role' => $role], "تم تعيين الدور {$request->role} بنجاح."); 
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error User Not Found: ' . $e->getMessage());
        }
    }

    public function revokeRole(Request $request, $user_id) {
        if (!Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError('ليس لديك صلاحية لسحب دور من مستخدم', 403);
            }
        $validator = Validator::make($request->all(), [
            'role' => ['required','string',Rule::exists('roles','name')]
        ],[
            'role.required' => 'يجب كتابة اسم الدور',
            'role.exists' => 'اسم الدور غير موجود في النظام',
            'role.string' => 'يجب أن يكون أسم الدور نصاً',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['role']);
            $user=$this->UserRepository->getById($user_id);
            $role=$this->RoleRepository->getByName($fields['role']);
            $role= $this->UserRepository->revokeRole($user,$role);
            return ApiResponseClass::sendResponse(['role' => $role], "تم سحب الدور {$request->role} بنجاح.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error User Not Found: ' . $e->getMessage());
        }
    }

    public function toggleBan(Request $request, $userId)
    {
        if (!Auth::user()->has_role('admin')) {
            return ApiResponseClass::sendError('ليس لديك صلاحية لحظر او فك حظر مستخدم', 403);
        }
        $validator = Validator::make($request->all(), [
            'is_banned'=>['required','boolean']
        ],[
            'is_banned.required' => 'يجب إدخال نوع الحظر',
            'is_banned.boolean'=>'يجب أن يكون نوع الحظر 1 او 0',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['is_banned']);
            $user=$this->UserRepository->update($fields,$userId);
            $message = $request->is_banned ? 'تم حظر المستخدم بنجاح' : 'تم فك حظر المستخدم بنجاح';
            return ApiResponseClass::sendResponse($user,$message);
        } catch (Exception $e) {
            return ApiResponseClass::sendError('An error occurred while processing the ban request : ' . $e->getMessage());
        }
        
        
    }
    
}
