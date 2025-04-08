<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ContactRepository $ContactRepository)
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
                return ApiResponseClass::sendError('ليس لديك صلاحية لاطلاع على رسائل التواصل', 403);
            }
            $contacts=$this->ContactRepository->index();
            return ApiResponseClass::sendResponse($contacts,'All Contacts retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Contacts: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' =>  ['required','string','max:255'],
            'email' =>  ['required','email','max:255'],
            'subject' => ['required','string','max:255'],
            'message' =>  ['required','string'],
        ],[
            'full_name.required' => 'يجب كتابة الاسم بالكامل',
            'full_name.string' => 'الاسم يجب أن يكون نصًا',
            'full_name.max' => 'الاسم لا يجب أن يتجاوز 255 حرفًا',
            'email.required' => 'يجب كتابة البريد الإلكتروني',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرفًا',
            'subject.required' => 'يجب كتابة الموضوع',
            'subject.string' => 'الموضوع يجب أن يكون نصًا',
            'subject.max' => 'الموضوع لا يجب أن يتجاوز 255 حرفًا',
            'message.required' => 'يجب كتابة الرسالة',
            'message.string' => 'الرسالة يجب أن تكون نصًا',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['full_name','email','subject','message']);
            $contact=$this->ContactRepository->store($fields);
            return ApiResponseClass::sendResponse($contact,'تم إرساله الرسالة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error sent  message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (!Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError('ليس لديك صلاحية لعرض الرسالة', 403);
            }
            $contact=$this->ContactRepository->getById($id);
            return ApiResponseClass::sendResponse($contact,' data getted  successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error returned Contact:  ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError('ليس لديك صلاحية لحدف الرسالة', 403);
            }
            $contact=$this->ContactRepository->getById($id);
            if($this->ContactRepository->delete($contact->id)){
                return ApiResponseClass::sendResponse($contact, "{$contact->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Contact with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Contact: ' . $e->getMessage());
        }
    }

    public function contactIndex(){
        try {
            $phone = config('contact.phone');
            $email = config('contact.email');
            $workingHours = config('contact.working_hours');
            return ApiResponseClass::sendResponse(['phone'=>$phone,'email'=>$email,'working'=>$workingHours],'All Contacts retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error returned Contact: ' . $e->getMessage());
        }
       
    }

    public function contactStore(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required','string'],
            'email' =>  ['required','email'],
            'working_hours' => ['required','string'],
        ],[
            'phone.required' => 'يجب كتابة رقم الجوال التواصل',
            'email.required' => 'يجب كتابة البريد الالكتروني',
            'working_hours.required' => 'يجب كتابة اوقات العمل',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        if (!Auth::user()->has_role('admin')) {
            return ApiResponseClass::sendError('ليس لديك صلاحية لحدف الرسالة', 403);
        }
        $fields=$request->only(['phone','email','working_hours']);
        $contactData = [
            'phone' => $fields['phone'],
            'email' =>  $fields['email'],
            'working_hours' => $fields['working_hours'],
        ];
        $content = "<?php\n\nreturn " . var_export($contactData, true) . ";\n";
        File::put(config_path('contact.php'), $content);
       
        return ApiResponseClass::sendResponse($contactData,'تم حفظ البيانات بنجاح');
    }
}
