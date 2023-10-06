<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::where('user_id',Auth::id())->orWhereHas('collaborators' , function($q){
            $q->where('user_id',Auth::id());
        })->get();
        return Inertia::render('books/index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('books/createBook');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'book_name' => 'required'
        ]);
        try {
            $book = Book::create([
                'name' => $request->book_name,
                'user_id' => Auth::id()
            ]);
            $chapters = $request->chapters;

            foreach ($chapters as $key => $value) {
                $chapter = Chapter::create([
                    'book_id' => $book->id,
                    'title' => $value['chapter_name'],
                    'chapter_no' => $value['chapter_no'],
                ]);
                $this->saveSections($chapter, $value['sections']);
            }
            return redirect()->route('books.index');
        } catch (\Throwable $th) {
            return redirect()->route('books.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::whereId($id)->with(['author', 'chapters', 'chapters.sections'])->first();
        $chapters = [];
        foreach ($book->chapters->toArray() as $key => $value) {
            $value['chapter_name'] = $value['title'];
            $value['sections'] = $this->buildTree($value['sections']);
            $chapters[] = $value;
        }


        return Inertia::render('books/editBook', ['book' => $book, 'chapters' => $chapters]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'book_name' => 'required'
        ]);
        try {
            $book = Book::whereId($id)->update([
                'name' => $request->book_name,
            ]);
            $chapters = $request->chapters;

            foreach ($chapters as $key => $value) {
                if (!empty($value['id'])) {
                    $chapter = Chapter::whereId($value['id'])->update([
                        'book_id' => $id,
                        'title' => $value['chapter_name'],
                        'chapter_no' => $value['chapter_no'],
                    ]);
                    $chapter = Chapter::whereId($value['id'])->first();
                    $this->saveSections($chapter, $value['sections']);
                } else {
                    $chapter = Chapter::create([
                        'book_id' => $id,
                        'title' => $value['chapter_name'],
                        'chapter_no' => $value['chapter_no'],
                    ]);
                    $this->saveSections($chapter, $value['sections']);
                }
            }
            return redirect()->route('books.index');
        } catch (\Throwable $th) {
            return redirect()->route('books.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Book::whereId($id)->delete();
        return redirect()->route('books.index');
    }
    /**
     * Create common function for saving sections
     */
    public function saveSections($chapter, $sections)
    {
        foreach ($sections as $key => $value) {
            if (!empty($value['id'])) {
                if (!empty($value['sections'])) {
                    Section::whereId($value['id'])->update([
                        'title' => $value['title'],
                        'text' => $value['text'],
                        'uuid' => $value['uuid'],
                        'parent_id' => $value['parent_id'] ?? null,
                    ]);
                    $this->saveSections($chapter, $value['sections']);
                } else {
                    Section::whereId($value['id'])->update([
                        'chapter_id' => $chapter->id,
                        'title' => $value['title'],
                        'text' => $value['text'],
                        'uuid' => $value['uuid'],
                        'parent_id' => $value['parent_id'] ?? null,
                    ]);
                }
            } else {
                if (!empty($value['sections'])) {
                    Section::create([
                        'chapter_id' => $chapter->id,
                        'title' => $value['title'],
                        'text' => $value['text'],
                        'uuid' => $value['uuid'],
                        'parent_id' => $value['parent_id'] ?? null,
                    ]);
                    $this->saveSections($chapter, $value['sections']);
                } else {
                    Section::create([
                        'chapter_id' => $chapter->id,
                        'title' => $value['title'],
                        'text' => $value['text'],
                        'uuid' => $value['uuid'],
                        'parent_id' => $value['parent_id'] ?? null,
                    ]);
                }
            }
        }
        return;
    }
    /**
     * for Create Hierachy structure
     */
    public function buildTree(array $elements, $parent_id = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parent_id) {
                $children = $this->buildTree($elements, $element['uuid']);
                if ($children) {
                    $element['sections'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
