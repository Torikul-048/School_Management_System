@php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n🔄 Checking database for users...\n\n";

try {
    $users = User::with('roles')->get();
    $count = $users->count();
    
    if ($count === 0) {
        echo "⚠️  No users found in database!\n";
        echo "💡 You need to run database seeders first:\n";
        echo "   php artisan db:seed\n\n";
    } else {
        echo "✅ Found {$count} users in database\n\n";
        echo "📋 Current Users:\n";
        echo str_repeat('─', 80) . "\n";
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ') ?: 'No Role';
            echo "📧 Email: {$user->email}\n";
            echo "👤 Name: {$user->name}\n";
            echo "🔑 Role: {$roles}\n";
            echo "📊 Status: " . ($user->status ?? 'N/A') . "\n";
            echo str_repeat('─', 80) . "\n";
        }
        
        echo "\n🔐 To reset all passwords to 'password123', run:\n";
        echo "   php artisan users:reset-passwords\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "💡 Make sure database is running and migrations are complete\n\n";
}
@endphp
