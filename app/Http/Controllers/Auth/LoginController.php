<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
class LoginController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try{
            $credentials = $request -> all();

            $validate = Validator::make($credentials,[
                'email' => 'required',
                'password' => 'required',
            ],[
                "email.required" => "邮箱不能为空",
                "password.required" => "密码不能为空",
            ]);
            // 自定义验证前端发送的 参数是否有
            if($validate->fails()) throw new \Exception($validate->errors()->first());

            $user = User::where($credentials)->first();
            
            $token = Auth::guard('api')->fromUser($user);
            // 检查用户状态
            if($user -> is_locked == '否'){
                return response()->json(['code' => 100, 'msg'=>'用户被锁定']);
            }
            return $this->respondWithToken($token);
        }catch(\Exception $e){
            return response()->json([ 
                'code' => 100,
                'msg' => $e->getMessage(),
                // 'lien' => $e->getLine()
            ]);
        }
        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * 格式化返回
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code' => 200,
            'msg' => '登陆成功',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
