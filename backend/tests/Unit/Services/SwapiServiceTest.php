<?php

namespace Tests\Unit\Services;

use App\Services\SwapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SwapiServiceTest extends TestCase
{
    public function test_can_fetch_people(): void
    {
        // swapi.dev returns full properties directly in the paginated list
        Http::fake([
            '*/people/*' => Http::response([
                'count'    => 1,
                'next'     => null,
                'previous' => null,
                'results'  => [
                    [
                        'name'       => 'Luke Skywalker',
                        'height'     => '172',
                        'mass'       => '77',
                        'hair_color' => 'blond',
                        'skin_color' => 'fair',
                        'eye_color'  => 'blue',
                        'birth_year' => '19BBY',
                        'gender'     => 'male',
                        'homeworld'  => 'https://swapi.dev/api/planets/1/',
                        'films'      => ['https://swapi.dev/api/films/1/'],
                        'url'        => 'https://swapi.dev/api/people/1/',
                    ],
                ],
            ], 200),
        ]);

        $service = new SwapiService();
        $people = $service->fetchPeople();

        $this->assertIsArray($people);
        $this->assertNotEmpty($people);
        $this->assertEquals('Luke Skywalker', $people[0]['name']);
        $this->assertEquals('172', $people[0]['height']);
        $this->assertEquals('https://swapi.dev/api/people/1/', $people[0]['swapi_url']);
        $this->assertArrayHasKey('film_urls', $people[0]);
    }

    public function test_can_fetch_films(): void
    {
        Http::fake([
            '*/films/*' => Http::response([
                'count'    => 1,
                'next'     => null,
                'previous' => null,
                'results'  => [
                    [
                        'title'         => 'A New Hope',
                        'episode_id'    => 4,
                        'opening_crawl' => 'It is a period of civil war...',
                        'director'      => 'George Lucas',
                        'producer'      => 'Gary Kurtz, Rick McCallum',
                        'release_date'  => '1977-05-25',
                        'characters'    => ['https://swapi.dev/api/people/1/'],
                        'url'           => 'https://swapi.dev/api/films/1/',
                    ],
                ],
            ], 200),
        ]);

        $service = new SwapiService();
        $films = $service->fetchFilms();

        $this->assertIsArray($films);
        $this->assertNotEmpty($films);
        $this->assertEquals('A New Hope', $films[0]['title']);
        $this->assertEquals(4, $films[0]['episode_id']);
        $this->assertArrayHasKey('character_urls', $films[0]);
    }

    public function test_handles_api_errors(): void
    {
        Http::fake([
            '*/people/*' => Http::response([], 500),
            '*/films/*'  => Http::response([], 503),
        ]);

        $service = new SwapiService();

        $people = $service->fetchPeople();
        $this->assertIsArray($people);
        $this->assertEmpty($people);

        $films = $service->fetchFilms();
        $this->assertIsArray($films);
        $this->assertEmpty($films);
    }
}
