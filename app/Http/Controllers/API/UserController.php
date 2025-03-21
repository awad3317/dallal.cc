<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\AvatarService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private UserRepository $UserRepository,private ImageService $ImageService,private AvatarService $AvatarService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
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
            return ApiResponseClass::sendError('You do not have permission to update this user.');
        }
        $validator = Validator::make($request->all(), [
            'username' => ['sometimes','string','regex:/^[A-Za-z0-9_]+$/', Rule::unique('users')->ignore($id)],
            'name' => ['sometimes','string','max:100'],
            'phone_number' => ['sometimes','string','min:10','max:15',],
            'image' => ['sometimes','image','max:2048'],
        ],[
            'username.string' => 'يجب أن يكون اسم المستخدم نصًا.',
            'username.regex' => 'يجب أن يحتوي اسم المستخدم على أحرف إنجليزية وأرقام وشرطة سفلية (_) فقط.',
            'username.unique' => 'اسم المستخدم هذا مستخدم بالفعل.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 100 حرف.',
            'phone_number.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone_number.min' => 'يجب أن يتكون رقم الهاتف من 10 أرقام على الأقل.',
            'phone_number.max' => 'يجب ألا يتجاوز رقم الهاتف 15 رقمًا.',
            'image.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميجابايت.',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['username','name','phone_number','image']);
            if ($request->hasFile('image')) {
                $fields['image']=$this->ImageService->saveImage($fields['image'],'images_users');
            }
            elseif($request->has('name')){
                $user = $this->UserRepository->getById($id);
                if ($request->input('name') !== $user->name) {
                    if ($this->AvatarService->isDefaultAvatar($user->image)) 
                    {
                        
                        unlink($user->image);
                        
                        $newDefaultAvatar = $this->AvatarService->createAvatar($request->input('name'));
                        $fields['image'] = $newDefaultAvatar;
                    }
                }
            }
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
            $roles=$this->UserRepository->assignRole($user_id,$fields['role']);
            return ApiResponseClass::sendResponse(['roles' => $roles], "تم تعيين الدور {$request->role} بنجاح."); 
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error User Not Found: ' . $e->getMessage());
        }
    }

    public function revokeRole(Request $request, $user_id) {
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
            $roles = $this->UserRepository->revokeRole($user_id, $fields['role']);
            return ApiResponseClass::sendResponse(['roles' => $roles], "تم سحب الدور {$request->role} بنجاح.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error User Not Found: ' . $e->getMessage());
        }
    }
}
