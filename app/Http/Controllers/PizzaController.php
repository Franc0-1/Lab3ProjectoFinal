<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pizza;
use App\Models\Category;
use Inertia\Inertia;

class PizzaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pizzas = Pizza::with('category')->get();
        $categories = Category::all();
        return Inertia::render('Pizzas/Index', [
            'pizzas' => $pizzas,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Pizzas/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'available' => 'boolean',
            'featured' => 'boolean',
            'preparation_time' => 'nullable|integer|min:1',
        ]);

        Pizza::create($validated);

        return redirect()->route('pizzas.index')->with('success', 'Pizza created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pizza = Pizza::with('category')->findOrFail($id);
        return Inertia::render('Pizzas/Show', [
            'pizza' => $pizza,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pizza = Pizza::findOrFail($id);
        $categories = Category::all();
        return Inertia::render('Pizzas/Edit', [
            'pizza' => $pizza,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pizza = Pizza::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'ingredients' => 'nullable|array',
            'available' => 'boolean',
            'featured' => 'boolean',
            'preparation_time' => 'nullable|integer|min:1',
        ]);

        $pizza->update($validated);

        return redirect()->route('pizzas.index')->with('success', 'Pizza updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pizza::destroy($id);
        return redirect()->route('pizzas.index')->with('success', 'Pizza deleted successfully!');
    }

    /**
     * Toggle the availability of a pizza.
     */
    public function toggleAvailability(string $id)
    {
        $pizza = Pizza::findOrFail($id);
        $pizza->available = !$pizza->available;
        $pizza->save();

        $status = $pizza->available ? 'disponible' : 'no disponible';
        return redirect()->route('pizzas.index')->with('success', "Pizza marcada como {$status}!");
    }
}
