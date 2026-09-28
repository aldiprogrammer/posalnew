<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_lupa_password_tampil(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Lupa Password')
            ->assertSee('Kirim Link Reset Password');
    }

    public function test_link_reset_password_terkirim_ke_email(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $url = $notification->toMail($user)->data['url'] ?? '';

            $this->assertStringStartsWith(rtrim(config('app.url'), '/').'/reset-password/', $url);
            $this->assertStringContainsString($user->email, $url);

            return true;
        });
    }

    public function test_gagal_kirim_jika_email_tidak_terdaftar(): void
    {
        Notification::fake();

        $this->post(route('password.email'), ['email' => 'tidak@ada.com'])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    public function test_email_wajib_diisi(): void
    {
        $this->post(route('password.email'), ['email' => ''])
            ->assertSessionHasErrors('email');
    }

    public function test_halaman_buat_password_baru_tampil_dengan_token(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertOk()
            ->assertSee('Buat Password Baru')
            ->assertSee($token)
            ->assertSee($user->email)
            ->assertDontSee('sudah kedaluwarsa');
    }

    public function test_halaman_beri_peringatan_jika_token_tidak_valid(): void
    {
        $user = User::factory()->create();

        $this->get(route('password.reset', ['token' => 'token-palsu', 'email' => $user->email]))
            ->assertOk()
            ->assertSee('sudah kedaluwarsa');
    }

    public function test_bisa_memperbarui_password_dengan_token_valid(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('password-baru-123', $user->fresh()->password));
    }

    public function test_gagal_perbarui_password_jika_token_tidak_valid(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => 'token-palsu',
            'email' => $user->email,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_konfirmasi_password_harus_sama(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru-123',
            'password_confirmation' => 'beda-123',
        ])->assertSessionHasErrors('password');
    }

    public function test_password_minimal_8_karakter(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ])->assertSessionHasErrors('password');
    }
}
