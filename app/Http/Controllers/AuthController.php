<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Upload da imagem de perfil, se fornecida
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_image' => $profileImagePath,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuário cadastrado com sucesso', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Verifica se usuário existe
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user) {
                $user->tokens()->delete();
                return response()->json(['message' => 'Logout realizado com sucesso'], 200);
            }

            return response()->json(['message' => 'Usuário não autenticado'], 401);

        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());

            return response()->json(['message' => 'Erro ao realizar logout. Por favor, tente novamente.'], 500);
        }
    }

    public function sendVerificationToken(Request $request)
    {
        $user = Auth::user();

        // Gerar o token de verificação
        $user->verification_token = bin2hex(random_bytes(16)); // Gera um token aleatório
        $user->save();

        // Enviar o e-mail de verificação
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'Token de verificação enviado para o e-mail: ' . $user->email .'.'], 200);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();

        // Verifica se o token está correto
        if ($user->verification_token === $request->token) {
            $user->email_verified_at = now();
            $user->verification_token = null; // Limpa o token após verificação
            $user->save();

            return response()->json(['message' => 'E-mail verificado com sucesso.'], 200);
        } else {
            return response()->json(['error' => 'Token inválido.'], 400);
        }
    }
}

