<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PizzaController extends Controller
{
    /**
     * Display a listing of the pizzas.
     */
    public function index()
    {
        $pizzas = Pizza::with('category')->paginate(10);
        return view('admin.pizzas.index', compact('pizzas'));
    }

    /**
     * Show the form for creating a new pizza.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.pizzas.create', compact('categories'));
    }

    /**
     * Store a newly created pizza in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pizzas', 'public');
        }

        Pizza::create($data);

        return redirect()->route('admin.pizzas.index')
            ->with('success', 'Pizza creada exitosamente.');
    }

    /**
     * Display the specified pizza.
     */
    public function show(Pizza $pizza)
    {
        return view('admin.pizzas.show', compact('pizza'));
    }

    /**
     * Show the form for editing the specified pizza.
     */
    public function edit(Pizza $pizza)
    {
        $categories = Category::all();
        return view('admin.pizzas.edit', compact('pizza', 'categories'));
    }

    /**
     * Update the specified pizza in storage.
     */
    public function update(Request $request, Pizza $pizza)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($pizza->image) {
                Storage::disk('public')->delete($pizza->image);
            }
            $data['image'] = $request->file('image')->store('pizzas', 'public');
        }

        $pizza->update($data);

        return redirect()->route('admin.pizzas.index')
            ->with('success', 'Pizza actualizada exitosamente.');
    }

    /**
     * Remove the specified pizza from storage.
     */
    public function destroy(Pizza $pizza)
    {
        if ($pizza->image) {
            Storage::disk('public')->delete($pizza->image);
        }

        $pizza->delete();

        return redirect()->route('admin.pizzas.index')
            ->with('success', 'Pizza eliminada exitosamente.');
    }

    /**
     * Toggle pizza availability.
     */
    public function toggleAvailability(Pizza $pizza)
    {
        $pizza->update([
            'is_available' => !$pizza->is_available
        ]);

        $status = $pizza->is_available ? 'disponible' : 'no disponible';
        
        return redirect()->back()
            ->with('success', "Pizza marcada como {$status}.");
    }
}
