<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Sanitizer\ScrapingSanitizer;
use Tests\TestCase;

class SanitizerTest extends TestCase
{
    /** @test */
    public function it_sanitizes_text_correctly()
    {
        $input = " <script>alert('XSS');</script> <p>Hola <strong>Mundo</strong></p> ";
        $expected = "<p>Hola <strong>Mundo</strong></p>";

        $this->assertEquals($expected, ScrapingSanitizer::sanitizeText($input));
    }

    /** @test */
    public function it_removes_extra_spaces_and_breaks()
    {
        $input = "  Texto   con     muchos   espacios   ";
        $expected = "Texto con muchos espacios";

        $this->assertEquals($expected, ScrapingSanitizer::sanitizeText($input));
    }

    /** @test */
    public function it_validates_urls()
    {
        $validUrl = "https://www.elmundo.es/noticia.html";
        $invalidUrl = "javascript:alert('XSS')";

        $this->assertEquals($validUrl, ScrapingSanitizer::sanitizeUrl($validUrl));
        $this->assertNull(ScrapingSanitizer::sanitizeUrl($invalidUrl));
    }
}
