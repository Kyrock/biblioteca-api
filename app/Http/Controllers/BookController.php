<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());
        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Book::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->validated());
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Book::destroy($id);
        return response()->json(null, 204);
    }
}
