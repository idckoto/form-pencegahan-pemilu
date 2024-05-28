// Import the necessary classes and namespaces
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// ...

// Define your API route
Route::put('/api/user', 'UserController@update')->middleware('auth:api');

// ...

// UserController.php
public function update(Request $request)
{
    try {
        $this->validate($request, [
            'email' => 'nullable|email|unique:users,email,' . Auth::user()->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|required_with:current_password|different:current_password',
        ]);

        $user = Auth::user();

        if ($request->filled('current_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                throw ValidationException::withMessages([
                    'current_password' => 'Password lama tidak cocok.',
                ]);
            }
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        $user->save();

        return response()->json(['message' => 'Profil berhasil diperbarui']);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan server'], 500);
    }
}
