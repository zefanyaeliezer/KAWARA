<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdminSeeder::class);
    }

    public function test_guest_sees_published_news_and_all_five_topics_without_login_button(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        News::create([
            'user_id' => $admin->id,
            'title' => 'Peluncuran Portal Berita KAWARA',
            'slug' => 'peluncuran-portal-berita-kawara',
            'category' => 'POLITIK & PEMERINTAHAN',
            'excerpt' => 'Portal berita baru resmi hadir.',
            'content' => 'Isi lengkap berita peluncuran portal KAWARA.',
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Peluncuran Portal Berita KAWARA');
        $response->assertSee('Merekam kabar, menyuarakan cerita.');
        $response->assertSee('MEDIA INFORMASI LOKAL KARAWANG');
        $response->assertSee('Jelajahi Kawara');
        $response->assertSee('Ekonomi');
        $response->assertSee('Pemerintahan');
        $response->assertSee('Wisata &amp; Lingkungan', false);
        $response->assertSee('Sosial');
        $response->assertSee('Pendidikan');
        $response->assertSee('https://www.instagram.com/kawara_updates?lqvp=MzFneDBzY2hlYms2', false);
        $response->assertSee('https://whatsapp.com/channel/0029Vb9QkUuBadmdn7ndp220', false);
        $response->assertDontSee('/admin/login');
        $response->assertDontSee('Mode Admin');

        foreach (News::CATEGORIES as $topic) {
            $response->assertSee($topic);
        }
    }

    public function test_guest_can_view_news_detail(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $news = News::create([
            'user_id' => $admin->id,
            'title' => 'Berita Pendidikan Terbaru',
            'slug' => 'berita-pendidikan-terbaru',
            'category' => 'PENDIDIKAN',
            'content' => 'Detail lengkap berita pendidikan hari ini.',
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('news.show', $news->slug));

        $response->assertOk();
        $response->assertSee('Berita Pendidikan Terbaru');
        $response->assertSee('Detail lengkap berita pendidikan hari ini.');
    }

    public function test_guest_is_redirected_when_accessing_admin_routes(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/berita/create')->assertRedirect('/admin/login');
        $this->post('/admin/berita', [
            'title' => 'Tidak Boleh Masuk',
            'category' => 'SOSIAL',
            'content' => 'Guest tidak bisa posting.',
        ])->assertRedirect('/admin/login');

        $this->assertDatabaseCount('news', 0);
    }

    public function test_admin_can_login_with_configured_credentials_and_manage_news(): void
    {
        Storage::fake('public');

        $this->get('/admin/login')->assertOk();

        $loginResponse = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $loginResponse->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();

        // Create news with real 1x1 PNG thumbnail (works without PHP GD extension)
        $tinyPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+X2dIAAAAASUVORK5CYII='
        );

        $createResponse = $this->post('/admin/berita', [
            'title' => 'Inovasi Wisata Ramah Lingkungan 2026',
            'category' => 'WISATA & LINGKUNGAN',
            'excerpt' => 'Ringkasan wisata ramah lingkungan.',
            'content' => 'Isi lengkap berita mengenai wisata ramah lingkungan.',
            'thumbnail' => UploadedFile::fake()->createWithContent('wisata.png', $tinyPng),
        ]);

        $createResponse->assertRedirect('/admin/dashboard');

        $news = News::firstOrFail();
        $this->assertSame('inovasi-wisata-ramah-lingkungan-2026', $news->slug);
        $this->assertNotNull($news->thumbnail);
        Storage::disk('public')->assertExists($news->thumbnail);

        // Update news
        $updateResponse = $this->put(route('admin.news.update', $news), [
            'title' => 'Pertumbuhan Ekonomi Daerah 2026',
            'category' => 'EKONOMI',
            'excerpt' => 'Ringkasan terbaru.',
            'content' => 'Isi berita yang telah diperbarui.',
        ]);

        $updateResponse->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Pertumbuhan Ekonomi Daerah 2026',
            'category' => 'EKONOMI',
        ]);

        // Logout and verify guest sees updated news
        $this->post('/admin/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->get('/')
            ->assertOk()
            ->assertSee('Pertumbuhan Ekonomi Daerah 2026')
            ->assertDontSee('Mode Admin');

        // Re-login and delete news
        $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ])->assertRedirect('/admin/dashboard');

        $this->delete(route('admin.news.destroy', $news))
            ->assertRedirect('/admin/dashboard');

        $this->assertDatabaseCount('news', 0);
    }

    public function test_admin_cannot_submit_topic_outside_allowed_list(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $response = $this->actingAs($admin)
            ->from('/admin/berita/create')
            ->post('/admin/berita', [
                'title' => 'Berita Dengan Topik Tidak Valid',
                'category' => 'Olahraga',
                'content' => 'Harus ditolak oleh validasi.',
            ]);

        $response->assertRedirect('/admin/berita/create');
        $response->assertSessionHasErrors('category');
        $this->assertDatabaseCount('news', 0);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'username' => 'admin',
            'password' => 'salahpassword',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
