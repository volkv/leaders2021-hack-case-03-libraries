<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;

use App\Models\Rubric;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->createAuthors();
        $this->createRubrics();
        $this->insertBooks();
    }


    public function insertBooks()
    {

        $rubrics = Rubric::get();
        $authors = Author::get();
        $data = [];


        foreach ($this->getDataset() as $book) {

            if (!$book['author_fullName']) {
                continue;
            }

            $year = preg_replace('/[^0-9]+/', '', $book['year']);
            if ($year < 1000 || $year > 2100) {
                $year = null;
            }

            $data[] = [
                'id' => $book['id'],
                'year' => $year,
                'isbn' => $book['isbn'] ? preg_replace('/[^0-9]+/', '', $book['isbn']) : null,
                'annotation' => $book['annotation'],
                'title' => $book['title'],
                'rubric_id' => $rubrics->where('name', $book['rubric_name'])->first()->id ?? null,
                'author_id' => $authors->where('full_name', $book['author_fullName'])->first()->id,
            ];

        }


        Book::upsert($data, ['id']);

    }

    public function createAuthors()
    {
        $data = [];

        foreach ($this->getDataset() as $book) {

            if (!$book['author_fullName']) {
                continue;
            }


            $data[$book['author_fullName']] = [

                'contribution' => $book['contribution'],
                'surname' => $book['author_surname'],
                'names' => $book['author_names'],
                'initials' => $book['author_initials'],
                'full_name' => $book['author_fullName'],
                'full_name_alt' => $book['author_fullNameAlt'],

            ];

        }

        Author::upsert($data, ['full_name']);
    }

    public function createRubrics()
    {
        $data = [];

        foreach ($this->getDataset() as $book) {

            if (!$book['rubric_name']) {
                continue;
            }

            $data[$book['rubric_name']] = [
                'name' => $book['rubric_name'],
            ];

        }

        Rubric::upsert($data, ['name']);
    }

    public function getDataset(): array
    {

        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

        return json_decode($data, true);

    }
}
