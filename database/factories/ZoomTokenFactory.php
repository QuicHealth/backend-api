<?php

namespace Database\Factories;

use App\Models\ZoomToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ZoomToken>
 */
class zoomTokenFactory extends Factory
{

    protected $model = ZoomToken::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {
        return [
            "access_token" => "eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI1YTcwMzlmZS1hNWNjLTRkOWYtYmM2MC0yMmE0MGYyNTJmNzcifQ.eyJ2ZXIiOjcsImF1aWQiOiIwMGJmZjA1MmU2MzBjZjhjYTU4OTEwNTc1OWJjNWE4NyIsImNvZGUiOiJOWlpxQVdsRDVnM1VZZVYwOEI2UVJ5eENwV0psNTdud3ciLCJpc3MiOiJ6bTpjaWQ6ZGNsdGJLeVpSakMxQjc0M3p4VG1kdyIsImdubyI6MCwidHlwZSI6MCwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJOTzYyZ2Nma1JWT2hxX08zMzRPQUtnIiwibmJmIjoxNjcxNDg3NzU4LCJleHAiOjE2NzE0OTEzNTgsImlhdCI6MTY3MTQ4Nzc1OCwiYWlkIjoiLXktNkVtTWRSYkdnOU5PcUxuTUJDUSIsImp0aSI6IjM1ZDBiNWZjLThiYzItNDhjZC1hYmJhLTY3MmVkMjNjNzM3OCJ9.x7de7KeigbxAtiMCQ9S9XipmXe1zN2cCqdfHsGEW3g8r9ejeamzcUruJ9MbRXsfmkIlkVgzzv7yZn84i3wJCPw",
            "token_type" => "bearer",
            "refresh_token" => "eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI4YTViYWVkNy1mOGZlLTQxYzMtYTFmNy04NjM5ODk4NjA0MGQifQ.eyJ2ZXIiOjcsImF1aWQiOiIwMGJmZjA1MmU2MzBjZjhjYTU4OTEwNTc1OWJjNWE4NyIsImNvZGUiOiJOWlpxQVdsRDVnM1VZZVYwOEI2UVJ5eENwV0psNTdud3ciLCJpc3MiOiJ6bTpjaWQ6ZGNsdGJLeVpSakMxQjc0M3p4VG1kdyIsImdubyI6MCwidHlwZSI6MSwidGlkIjowLCJhdWQiOiJodHRwczovL29hdXRoLnpvb20udXMiLCJ1aWQiOiJOTzYyZ2Nma1JWT2hxX08zMzRPQUtnIiwibmJmIjoxNjcxNDg3NzU4LCJleHAiOjIxNDQ1Mjc3NTgsImlhdCI6MTY3MTQ4Nzc1OCwiYWlkIjoiLXktNkVtTWRSYkdnOU5PcUxuTUJDUSIsImp0aSI6ImZhNDNmMTk4LWY2OWEtNDgyMC1iNGY5LTc3ZjAwZjI2ZjBmZSJ9.1MqXeX-oAOtvd3_ym1r7jRXL82m9YMY8nymDIFoL7Ee_OQ2aEAhGwWsUuy17JqHgchde-UVTmBQ7Gyg6q_4EpQ",
            "expires_in" => 3599,
            "scope" => "meeting:read meeting:write meeting_token:read:live_streaming meeting_token:read:local_recording recording:read recording:write user:read user:write user_info:read user_zak:read webinar:read webinar:write webinar_token:read:live_streaming webinar_token:read:local_recording zoomapp:inmeeting zoomapp:inwebinar",
        ];
    }
}