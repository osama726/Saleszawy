<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\ContactStatus;
use App\Models\ContactRequest;
use App\Settings\ContactSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ContactRequestApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, string>  $override
     * @return array<string, string>
     */
    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'أحمد محمد',
            'phone' => '+20 100 123 4567',
            'message' => 'عايز عرض توضيحي',
            'website' => '',
        ], $override);
    }

    private function decodedText(?string $url): string
    {
        parse_str((string) parse_url((string) $url, PHP_URL_QUERY), $query);

        return (string) ($query['text'] ?? '');
    }

    public function test_stores_a_contact_request(): void
    {
        $this->postJson('/api/v1/contact-requests', $this->payload(['source' => 'home']), ['User-Agent' => 'PHPUnit'])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'تم استلام طلبك وسنتواصل معك قريباً');

        $this->assertDatabaseHas('contact_requests', [
            'name' => 'أحمد محمد',
            'phone' => '+20 100 123 4567',
            'source' => 'home',
            'status' => ContactStatus::New->value,
            'user_agent' => 'PHPUnit',
        ]);
    }

    public function test_returns_a_whatsapp_link_with_the_visitor_data(): void
    {
        $response = $this->postJson('/api/v1/contact-requests', $this->payload())->assertCreated();

        $url = $response->json('data.whatsapp_url');

        $this->assertStringStartsWith('https://wa.me/201212020075?text=', $url);

        $text = $this->decodedText($url);
        $this->assertStringContainsString('أحمد محمد', $text);
        $this->assertStringContainsString('+20 100 123 4567', $text);
        $this->assertStringContainsString('عايز عرض توضيحي', $text);
    }

    public function test_whatsapp_text_has_no_trailing_blank_lines_without_a_message(): void
    {
        $response = $this->postJson('/api/v1/contact-requests', $this->payload(['message' => '']))->assertCreated();

        $text = $this->decodedText($response->json('data.whatsapp_url'));

        $this->assertSame($text, rtrim($text));
        $this->assertStringContainsString('أحمد محمد', $text);
    }

    public function test_whatsapp_number_and_template_come_from_the_settings(): void
    {
        $settings = app(ContactSettings::class);
        $settings->whatsapp_number = '+20 100 000 0000';
        $settings->whatsapp_message_template = 'طلب من {name}';
        $settings->save();

        $url = $this->postJson('/api/v1/contact-requests', $this->payload())->json('data.whatsapp_url');

        $this->assertStringStartsWith('https://wa.me/201000000000?text=', $url);
        $this->assertSame('طلب من أحمد محمد', $this->decodedText($url));
    }

    public function test_message_is_optional(): void
    {
        $this->postJson('/api/v1/contact-requests', ['name' => 'سارة', 'phone' => '01001234567'])
            ->assertCreated();

        $this->assertDatabaseCount('contact_requests', 1);
    }

    public function test_requires_name_and_phone(): void
    {
        $this->postJson('/api/v1/contact-requests', [])
            ->assertStatus(422)
            ->assertJsonPath('code', 'VALIDATION_ERROR')
            ->assertJsonValidationErrors(['name', 'phone'], 'errors');
    }

    public function test_rejects_an_invalid_phone(): void
    {
        $this->postJson('/api/v1/contact-requests', $this->payload(['phone' => 'abc']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['phone'], 'errors');

        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_arabic_digits_in_the_phone_are_accepted(): void
    {
        $this->postJson('/api/v1/contact-requests', $this->payload(['phone' => '٠١٠٠١٢٣٤٥٦٧']))
            ->assertCreated();

        $this->assertDatabaseHas('contact_requests', ['phone' => '01001234567']);
    }

    public function test_honeypot_stores_nothing_but_answers_exactly_like_a_real_visitor(): void
    {
        $response = $this->postJson('/api/v1/contact-requests', $this->payload(['website' => 'http://spam.test']))
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertNotNull($response->json('data.whatsapp_url'));
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_is_limited_to_five_submissions_per_minute(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/contact-requests', $this->payload())->assertCreated();
        }

        $this->postJson('/api/v1/contact-requests', $this->payload())
            ->assertStatus(429)
            ->assertJsonPath('code', 'TOO_MANY_REQUESTS');

        $this->assertSame(5, ContactRequest::count());
    }
}
