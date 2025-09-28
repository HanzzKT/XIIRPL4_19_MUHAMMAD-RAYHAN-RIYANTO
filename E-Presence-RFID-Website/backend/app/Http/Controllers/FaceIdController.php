<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presence;
use App\Models\FaceRegistration;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaceIdController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'face_data' => 'required|string',
                'anti_spoofing_score' => 'numeric|min:0|max:1',
                'confidence_threshold' => 'numeric|min:0.1|max:1.0'
            ]);

            // Check if Face ID is enabled in settings
            $settings = Settings::first();
            if (!$settings || !$settings->face_recognition_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face ID tidak diaktifkan dalam sistem'
                ], 403);
            }

            // Anti-spoofing check
            $antiSpoofingScore = $request->anti_spoofing_score ?? 0;
            $confidenceThreshold = $settings->face_confidence_threshold ?? 0.7;
            
            if ($settings->anti_spoofing_enabled && $antiSpoofingScore < 0.7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal verifikasi anti-spoofing. Gunakan wajah asli.',
                    'antiSpoofingScore' => $antiSpoofingScore
                ], 401);
            }

            // Get all approved face registrations for comparison
            $faceRegistrations = FaceRegistration::where('status', 'approved')->get();
            
            if ($faceRegistrations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada Face ID yang terdaftar dalam sistem'
                ], 404);
            }

            // Simple face matching (in real implementation, use proper face recognition)
            // For demo purposes, we'll simulate face recognition
            $matchedUser = $this->simulateFaceRecognition($request->face_data, $faceRegistrations);
            
            if (!$matchedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wajah tidak dikenali dalam sistem',
                    'confidence' => 0
                ], 401);
            }

            // Process attendance
            $attendanceResult = $this->processAttendance($matchedUser, $request);
            
            return response()->json([
                'success' => true,
                'message' => $attendanceResult['message'],
                'type' => $attendanceResult['type'],
                'user' => [
                    'id' => $matchedUser->id,
                    'name' => $matchedUser->name,
                    'nis' => $matchedUser->nis ?? null
                ],
                'confidence' => 0.85, // Simulated confidence
                'antiSpoofingScore' => $antiSpoofingScore,
                'attendance' => $attendanceResult['attendance'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Face ID authentication error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'face_images' => 'required|array|min:1|max:3',
                'face_images.*' => 'required|string',
                'user_id' => 'nullable|exists:users,id'
            ]);

            $userId = $request->user_id ?? Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Check if user already has registration
            $existingRegistration = FaceRegistration::where('user_id', $userId)->first();
            if ($existingRegistration) {
                // Delete existing registration to allow re-registration
                $existingRegistration->delete();
            }

            $faceImages = $request->face_images;
            
            // Ensure we have at least 1 image, pad to 3 if needed
            while (count($faceImages) < 3) {
                $faceImages[] = $faceImages[count($faceImages) - 1];
            }

            // Create face registration - auto approved for E-Presence
            $registration = FaceRegistration::create([
                'user_id' => $userId,
                'face_data' => $faceImages[0] ?? null,
                'face_data_2' => $faceImages[1] ?? null,
                'face_data_3' => $faceImages[2] ?? null,
                'status' => 'approved', // Auto approve in E-Presence
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'anti_spoofing_score' => 0.85, // Simulated score
                'confidence_score' => 0.90, // Simulated score
                'notes' => 'Auto-approved Face ID registration for E-Presence system'
            ]);

            Log::info('Face ID registration successful', [
                'user_id' => $userId,
                'registration_id' => $registration->id,
                'images_count' => count($faceImages)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi Face ID berhasil! Anda dapat menggunakan Face ID untuk absensi.',
                'registration' => [
                    'id' => $registration->id,
                    'status' => $registration->status,
                    'approved_at' => $registration->approved_at,
                    'images_count' => count(array_filter([$registration->face_data, $registration->face_data_2, $registration->face_data_3]))
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Face ID registration error', [
                'user_id' => $request->user_id ?? Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkRegistration(Request $request)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'has_registration' => false,
                    'status' => 'not_authenticated'
                ]);
            }

            $registration = FaceRegistration::where('user_id', $userId)->first();

            if (!$registration) {
                return response()->json([
                    'has_registration' => false,
                    'status' => 'no_registration'
                ]);
            }

            return response()->json([
                'has_registration' => true,
                'status' => $registration->status,
                'approved_at' => $registration->approved_at,
                'images_count' => count(array_filter([$registration->face_data, $registration->face_data_2, $registration->face_data_3]))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'has_registration' => false,
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function attendance(Request $request)
    {
        try {
            $request->validate([
                'face_data' => 'required|string',
                'location' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric'
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna belum terautentikasi'
                ], 401);
            }

            $user = Auth::user();
            
            // Check user's face registration
            $userRegistration = FaceRegistration::where('user_id', $user->id)
                                              ->where('status', 'approved')
                                              ->first();
            
            if (!$userRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum terdaftar Face ID atau belum disetujui'
                ], 403);
            }

            // Simulate face recognition (in real implementation, use proper face recognition)
            $recognitionResult = $this->simulateUserFaceRecognition($request->face_data, $userRegistration);
            
            if (!$recognitionResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $recognitionResult['message'],
                    'confidence' => $recognitionResult['confidence'] ?? 0
                ], 401);
            }

            // Process attendance
            $attendanceResult = $this->processAttendance($user, $request);
            
            return response()->json([
                'success' => true,
                'message' => $attendanceResult['message'],
                'type' => $attendanceResult['type'],
                'time' => now()->format('H:i'),
                'date' => now()->format('d/m/Y'),
                'confidence' => $recognitionResult['confidence'],
                'attendance' => $attendanceResult['attendance'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Face ID attendance error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    private function simulateFaceRecognition($faceData, $registrations)
    {
        // In a real implementation, this would use actual face recognition algorithms
        // For demo purposes, we'll simulate matching based on some criteria
        
        foreach ($registrations as $registration) {
            $user = User::find($registration->user_id);
            if ($user) {
                // Simulate 85% chance of successful recognition for registered users
                if (rand(1, 100) <= 85) {
                    return $user;
                }
            }
        }
        
        return null;
    }

    private function simulateUserFaceRecognition($faceData, $userRegistration)
    {
        // Simulate face recognition for specific user
        // In real implementation, compare face_data with registered faces
        
        $confidence = rand(70, 95) / 100; // Random confidence between 0.7-0.95
        
        if ($confidence >= 0.7) {
            return [
                'success' => true,
                'confidence' => $confidence,
                'message' => 'Wajah berhasil dikenali'
            ];
        } else {
            return [
                'success' => false,
                'confidence' => $confidence,
                'message' => 'Wajah tidak dikenali dengan cukup yakin'
            ];
        }
    }

    private function processAttendance($user, $request)
    {
        $today = now()->format('Y-m-d');
        $existingPresence = Presence::where('nis', $user->nis ?? $user->username)
                                  ->where('tanggal', $today)
                                  ->first();

        $now = now();
        $currentTime = $now->format('H:i:s');

        if ($existingPresence) {
            // If already checked in but not checked out
            if ($existingPresence->jam_masuk && !$existingPresence->jam_keluar) {
                $existingPresence->jam_keluar = $currentTime;
                $existingPresence->save();

                return [
                    'type' => 'check_out',
                    'message' => 'Absen keluar berhasil pada ' . $now->format('H:i'),
                    'attendance' => [
                        'check_in' => $existingPresence->jam_masuk,
                        'check_out' => $existingPresence->jam_keluar,
                        'date' => $existingPresence->tanggal
                    ]
                ];
            } else {
                return [
                    'type' => 'completed',
                    'message' => 'Anda sudah melakukan absen masuk dan keluar hari ini',
                    'attendance' => [
                        'check_in' => $existingPresence->jam_masuk,
                        'check_out' => $existingPresence->jam_keluar,
                        'date' => $existingPresence->tanggal
                    ]
                ];
            }
        }

        // Create new attendance record
        $presence = new Presence();
        $presence->nis = $user->nis ?? $user->username;
        $presence->tanggal = $today;
        $presence->jam_masuk = $currentTime;
        $presence->status = $this->determineAttendanceStatus($currentTime);
        $presence->save();

        return [
            'type' => 'check_in',
            'message' => 'Absen masuk berhasil pada ' . $now->format('H:i') . 
                        ($presence->status === 'Terlambat' ? ' (Terlambat)' : ' (Tepat waktu)'),
            'attendance' => [
                'check_in' => $presence->jam_masuk,
                'check_out' => null,
                'date' => $presence->tanggal,
                'status' => $presence->status
            ]
        ];
    }

    private function determineAttendanceStatus($time)
    {
        // Convert time to minutes for easier comparison
        $timeParts = explode(':', $time);
        $timeInMinutes = ($timeParts[0] * 60) + $timeParts[1];
        
        // 07:00 = 420 minutes
        $cutoffTime = 7 * 60; // 7:00 AM
        
        return $timeInMinutes > $cutoffTime ? 'Terlambat' : 'Hadir';
    }
}
