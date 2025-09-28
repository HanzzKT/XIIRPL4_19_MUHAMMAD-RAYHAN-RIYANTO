<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\FaceRegistration;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::first() ?? new Settings();
        $userFaceRegistration = null;
        
        if (Auth::check()) {
            $userFaceRegistration = FaceRegistration::where('user_id', Auth::id())->first();
        }
        
        return view('settings.index', compact('settings', 'userFaceRegistration'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'attendance_method' => 'required|in:rfid,face_id,both',
            'face_recognition_enabled' => 'boolean',
            'anti_spoofing_enabled' => 'boolean',
            'face_confidence_threshold' => 'numeric|min:0.1|max:1.0'
        ]);
        
        $settings = Settings::first();
        
        if (!$settings) {
            // Create new settings record
            $settings = Settings::create([
                'user_id' => Auth::id(),
                'attendance_method' => $request->attendance_method,
                'face_recognition_enabled' => $request->has('face_recognition_enabled'),
                'anti_spoofing_enabled' => $request->has('anti_spoofing_enabled'),
                'face_confidence_threshold' => $request->face_confidence_threshold ?? 0.7,
                'service' => 'face_id',
                'api_key' => null, // Face ID doesn't need API key
                'connected_at' => now()
            ]);
        } else {
            // Update existing settings
            $settings->update([
                'attendance_method' => $request->attendance_method,
                'face_recognition_enabled' => $request->has('face_recognition_enabled'),
                'anti_spoofing_enabled' => $request->has('anti_spoofing_enabled'),
                'face_confidence_threshold' => $request->face_confidence_threshold ?? 0.7
            ]);
        }
        
        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan! Menu sidebar telah diperbarui.');
    }
    
    public function faceId()
    {
        return view('settings.face-id');
    }
    
    public function registerFace()
    {
        return view('settings.register-face');
    }
}
