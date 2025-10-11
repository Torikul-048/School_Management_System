<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'admin@school.com')->first();

if ($user) {
    $user->password = Illuminate\Support\Facades\Hash::make('password123');
    $user->save();
    echo "✓ Admin password updated to 'password123'\n";
    echo "✓ You can now login with: admin@school.com / password123\n";
} else {
    echo "✗ Admin user not found\n";
}
