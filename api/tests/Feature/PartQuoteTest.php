<?php

namespace Tests\Feature;

use App\Part;
use App\Quote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartQuoteTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_part_can_be_submitted_for_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => 'TestName',
                'email' => 'TestEmail',
                'phone' => '07708880337',
                'more_info' => 'This is some extra information about the quote',
                'part' => UploadedFile::fake()->create('example-cad-file.stl', 1000)
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();

        $this->assertEquals('TestName', $quotes[0]->name);

        $this->assertCount(1, $quotes);
        $this->assertCount(1, $parts);
        $this->assertEquals('TestName', $quotes[0]->name);
        $this->assertEquals('TestEmail', $quotes[0]->email);
        $this->assertEquals('07708880337', $quotes[0]->phone);
        $this->assertEquals('This is some extra information about the quote', $quotes[0]->more_info);
        $this->assertEquals($quotes[0]->part->url, $parts[0]->url);
        $this->assertTrue(Storage::exists($parts[0]->url));

        Storage::delete($parts[0]->url);

        $response->assertStatus(201);

    }

    /** @test */
    public function more_info_is_not_required_to_create_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => 'TestName',
                'email' => 'TestEmail',
                'phone' => '07708880337',
                'more_info' => '',
                'part' => UploadedFile::fake()->create('example-cad-file.stl', 1000)
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();

        $this->assertEquals('TestName', $quotes[0]->name);
        $this->assertCount(1, $quotes);
        $this->assertCount(1, $parts);
        $this->assertEquals('TestName', $quotes[0]->name);
        $this->assertEquals('TestEmail', $quotes[0]->email);
        $this->assertEquals('07708880337', $quotes[0]->phone);
        $this->assertEquals('', $quotes[0]->more_info);
        $this->assertEquals($quotes[0]->part->url, $parts[0]->url);
        $this->assertTrue(Storage::exists($parts[0]->url));

        Storage::delete($parts[0]->url);

        $response->assertStatus(201);
    }

    /** @test */
    public function a_name_is_required_to_create_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => '',
                'email' => 'TestEmail',
                'phone' => '07708880337',
                'more_info' => 'This is some extra information about the quote',
                'part' => UploadedFile::fake()->create('example-cad-file.stl', 1000)
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();


        $this->assertCount(0, $quotes);
        $this->assertCount(0, $parts);

        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'name' => ['The name field is required.']
                    ]
                ]
            );
    }

    /** @test */
    public function an_email_is_required_to_create_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => 'TestName',
                'email' => '',
                'phone' => '07708880337',
                'more_info' => 'This is some extra information about the quote',
                'part' => UploadedFile::fake()->create('example-cad-file.stl', 1000)
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();


        $this->assertCount(0, $quotes);
        $this->assertCount(0, $parts);

        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'email' => ['The email field is required.']
                    ]
                ]
            );
    }

    /** @test */
    public function a_phone_is_required_to_create_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => 'TestName',
                'email' => 'TestEmail',
                'phone' => '',
                'more_info' => 'This is some extra information about the quote',
                'part' => UploadedFile::fake()->create('example-cad-file.stl', 1000)
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();


        $this->assertCount(0, $quotes);
        $this->assertCount(0, $parts);

        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'phone' => ['The phone field is required.']
                    ]
                ]
            );
    }

    /** @test */
    public function a_part_is_required_to_create_a_quote()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/quote/submit',
            [
                'name' => 'TestName',
                'email' => 'TestEmail',
                'phone' => '07708880337',
                'more_info' => 'This is some extra information about the quote'
            ]
        );

        $quotes = Quote::with('part')->get();
        $parts = Part::all();


        $this->assertCount(0, $quotes);
        $this->assertCount(0, $parts);

        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'part' => ['The part field is required.']
                    ]
                ]
            );
    }

    /** @test */
    public function all_quotes_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        factory(Quote::class, 2)->create();
        $response = $this->get('/api/quotes');

        $quotes = Quote::all();

        $response->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        [
                            'name' => $quotes[0]->name,
                            'email' => $quotes[0]->email,
                            'phone' => $quotes[0]->phone,
                            'more_info' => $quotes[0]->more_info,
                            'part' => Storage::get($quotes[0]->part->url)
                        ],
                        [
                            'name' => $quotes[1]->name,
                            'email' => $quotes[1]->email,
                            'phone' => $quotes[1]->phone,
                            'more_info' => $quotes[1]->more_info,
                            'part' => Storage::get($quotes[1]->part->url)
                        ]
                    ]
                ]
            );

        Storage::delete($quotes[0]->part->url);
        Storage::delete($quotes[1]->part->url);

    }

    /** @test */
    public function a_single_quote_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        factory(Quote::class, 2)->create();
        $response = $this->get('/api/quotes/2');

        $quotes = Quote::all();

        $response->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'name' => $quotes[1]->name,
                        'email' => $quotes[1]->email,
                        'phone' => $quotes[1]->phone,
                        'more_info' => $quotes[1]->more_info,
                        'part' => Storage::get($quotes[1]->part->url)
                    ]
                ]
            );

        Storage::delete($quotes[0]->part->url);
        Storage::delete($quotes[1]->part->url);
    }

    /** @test */
    public function only_existing_quotes_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        factory(Quote::class, 2)->create();
        $response = $this->get('/api/quotes/4');

        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'quoteId' => 'Quote 4 not found.'
                    ]
                ]
            );

        $quotes = Quote::all();
        Storage::delete($quotes[0]->part->url);
        Storage::delete($quotes[1]->part->url);
    }


}
