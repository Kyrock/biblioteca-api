<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function creates_a_book_successfully()
    {

        $payload = [
            'title' => 'title test',
            'author' => 'Author test',
            'published_year' => '2020',
            'genre' => 'Genre test'
        ];

        $response = $this->postJson('/api/books', $payload);

        $response->assertStatus(201)->assertJsonFragment([
            'title' => 'title test',
            'author' => 'Author test',
            'published_year' => '2020',
            'genre' => 'Genre test'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'title test',
            'author' => 'Author test',
            'published_year' => '2020',
            'genre' => 'Genre test'
        ]);
    }

    #[Test]
    public function creates_a_book_with_errors()
    {
        $payload = [
            'title' => '',
            'published_year' => 'asdfas',
        ];

        $response = $this->postJson('/api/books', $payload);

        $response->assertStatus(422)->assertJsonValidationErrors([
            'title',
            'published_year',
            'author',
            'genre'
        ]);
    }

    #[Test]
    public function get_books_successfully()
    {
        \App\Models\Book::create([
            'title' => 'title test 1',
            'author' => 'Author test 1',
            'published_year' => '2020',
            'genre' => 'Genre test 1'
        ]);

        \App\Models\Book::create([
            'title' => 'title test 2',
            'author' => 'Author test 2',
            'published_year' => '2021',
            'genre' => 'Genre test 2'
        ]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)->assertJsonCount(2);
    }

    #[Test]
    public function get_book_by_id_successfully()
    {
        \App\Models\Book::create([
            'title' => 'title test 1',
            'author' => 'Author test 1',
            'published_year' => '2020',
            'genre' => 'Genre test 1'
        ]);

        $book_to_test = \App\Models\Book::create([
            'title' => 'title test 2',
            'author' => 'Author test 2',
            'published_year' => '2021',
            'genre' => 'Genre test 2'
        ]);

        $response = $this->getJson("/api/books/{$book_to_test->id}");

        $response->assertStatus(200)->assertJsonFragment([
            'title' => 'title test 2',
            'author' => 'Author test 2',
            'published_year' => '2021',
            'genre' => 'Genre test 2'
        ]);
    }

    #[Test]
    public function delete_book_successfully()
    {
        $book = \App\Models\Book::create([
            'title' => 'title test 1',
            'author' => 'Author test 1',
            'published_year' => '2020',
            'genre' => 'Genre test 1'
        ]);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id
        ]);
    }

    #[Test]
    public function update_book_successfully()
    {
        $book = \App\Models\Book::create([
            'title' => 'title test 1',
            'author' => 'Author test 1',
            'published_year' => '2020',
            'genre' => 'Genre test 1'
        ]);

        $payload = [
            'title' => 'title test 1 new',
            'author' => 'Author test 1 new',
            'published_year' => '2021',
            'genre' => 'Genre test 1 new'
        ];

        $response = $this->putJson("/api/books/{$book->id}", $payload);

        $response->assertStatus(200)->assertJsonFragment([
            'title' => 'title test 1 new',
            'author' => 'Author test 1 new',
            'published_year' => '2021',
            'genre' => 'Genre test 1 new'
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'title test 1 new',
            'author' => 'Author test 1 new',
            'published_year' => '2021',
            'genre' => 'Genre test 1 new'
        ]);
    }
}
