<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CollaboratorController extends Controller
{
    /**
     * Get collaborate data and render it to UI
     * @param $id
     */
    public function index($id)
    {
        $collaborators = Book::whereId($id)->first()->collaborators;
        $users = User::where('id', '!=', Auth::id())->get();
        return Inertia::render('Collaborators/index', ['collaborators' => $collaborators, 'users' => $users, 'id' => $id]);
    }
    /**
     * Add collaborate to book
     */
    public function add(Request $request)
    {
        try {
            $book = Book::whereId($request->book_id)->first();
            $collaborators = $book->collaborators->pluck('id')->toArray();
            if (!in_array($request->collaborator_id, $collaborators)) {
                $collaborators[] = $request->collaborator_id;
            }
            $book->collaborators()->sync($collaborators);
            return redirect()->route('collaborators.index', $request->book_id);
        } catch (\Throwable $th) {
            return redirect()->route('collaborators.index', $request->book_id);
        }
    }
    /**
     * remove collaborate from book
     */
    public function remove(Request $request)
    {
        try {
            $book = Book::whereId($request->book_id)->first();
            $collaborators = $book->collaborators->pluck('id')->toArray();
            if (in_array($request->collaborator_id, $collaborators)) {
                // $collaborators[] = $request->collaborator_id;
                array_splice($collaborators, array_search($request->collaborator_id, $collaborators), 1);
            }
            $book->collaborators()->sync($collaborators);
            return redirect()->route('collaborators.index', $request->book_id);
        } catch (\Throwable $th) {
            return redirect()->route('collaborators.index', $request->book_id);
        }
    }
}
