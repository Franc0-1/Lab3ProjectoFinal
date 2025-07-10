<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pizza;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $cartItems = [];
        
        // Static pizza data (same as in home view)
        $pizzas = [
            ['id' => 1, 'nombre' => 'MUZZARELLA', 'ingredientes' => ['Salsa', 'muzza', 'oregano'], 'precio' => 5500, 'imagen' => 'pizzas/pizza-1.webp'],
            ['id' => 2, 'nombre' => 'MUZZA CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'oregano'], 'precio' => 6000, 'imagen' => 'pizzas/pizza-2.webp'],
            ['id' => 3, 'nombre' => 'FUGAZZETA', 'ingredientes' => ['Salsa', 'muzza', 'cebolla', 'oregano'], 'precio' => 6500, 'imagen' => 'pizzas/pizza-3.webp'],
            ['id' => 4, 'nombre' => 'NAPOLITANA', 'ingredientes' => ['Salsa', 'muzza', 'tomates', 'oregano', 'aceite de ajo'], 'precio' => 6500, 'imagen' => 'pizzas/pizza-4.webp'],
            ['id' => 5, 'nombre' => 'NAPO CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'tomates', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-5.webp'],
            ['id' => 6, 'nombre' => 'FUGA CON JAMON', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'cebolla', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-6.webp'],
            ['id' => 7, 'nombre' => 'ESPECIAL', 'ingredientes' => ['Salsa', 'muzza', 'jamon', 'tomates', 'cebolla', 'oregano'], 'precio' => 7000, 'imagen' => 'pizzas/pizza-7.webp'],
            ['id' => 8, 'nombre' => '2 MUZZA', 'ingredientes' => ['2 Pizzas Muzzarella'], 'precio' => 10000, 'imagen' => 'pizzas/pizza-8.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
            ['id' => 9, 'nombre' => '1 MUZZA + 1 ESPECIAL', 'ingredientes' => ['1 Pizza Muzzarella', '1 Pizza Especial'], 'precio' => 12000, 'imagen' => 'pizzas/pizza-9.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
            ['id' => 10, 'nombre' => '1 NAPO + 1 FUGA', 'ingredientes' => ['1 Pizza Napolitana', '1 Pizza Fugazzeta'], 'precio' => 12000, 'imagen' => 'pizzas/pizza-10.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
            ['id' => 11, 'nombre' => '1 NAPO + 1 FUGA (CON JAMÓN)', 'ingredientes' => ['1 Pizza Napolitana con Jamón', '1 Pizza Fugazzeta con Jamón'], 'precio' => 13000, 'imagen' => 'pizzas/pizza-11.webp', 'esPromo' => true, 'cantidadPizzas' => 2],
        ];
        
        foreach ($cart as $pizzaId => $quantity) {
            $pizza = collect($pizzas)->firstWhere('id', $pizzaId);
            if ($pizza) {
                $itemTotal = $pizza['precio'] * $quantity;
                $total += $itemTotal;
                $cartItems[] = [
                    'pizza' => $pizza,
                    'quantity' => $quantity,
                    'total' => $itemTotal
                ];
            }
        }
        
        // Check if user is authenticated and redirect to login if cart is not empty
        if (!Auth::check() && !empty($cart)) {
            return Inertia::render('Cart/Index', [
                'cartItems' => $cartItems,
                'total' => $total,
                'count' => array_sum($cart),
                'requiresAuth' => true
            ]);
        }
        
        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'count' => array_sum($cart),
            'requiresAuth' => false
        ]);
    }
    
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'pizza_id' => 'required|integer|min:1|max:11',
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $pizzaId = $request->pizza_id;
        $quantity = $request->quantity;
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$pizzaId])) {
            $cart[$pizzaId] += $quantity;
        } else {
            $cart[$pizzaId] = $quantity;
        }
        
        // Ensure quantity doesn't exceed 10
        if ($cart[$pizzaId] > 10) {
            $cart[$pizzaId] = 10;
        }
        
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Pizza agregada al carrito',
            'quantity' => $cart[$pizzaId],
            'count' => array_sum($cart)
        ]);
    }
    
    /**
     * Update item quantity in cart
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id] = $request->quantity;
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'count' => array_sum($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item no encontrado en el carrito'
        ], 404);
    }
    
    /**
     * Remove item from cart (decrease quantity by 1)
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id] -= 1;
            
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
            }
            
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Item actualizado en el carrito',
                'quantity' => $cart[$id] ?? 0,
                'count' => array_sum($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item no encontrado en el carrito'
        ], 404);
    }
    
    /**
     * Delete item completely from cart
     */
    public function delete($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Item eliminado del carrito',
            'count' => array_sum($cart ?? [])
        ]);
    }
    
    /**
     * Clear all items from cart
     */
    public function clear()
    {
        Session::forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'count' => 0
        ]);
    }
    
    /**
     * Get cart count
     */
    public function count()
    {
        $cart = Session::get('cart', []);
        $count = array_sum($cart);
        
        return response()->json([
            'count' => $count,
            'items' => collect($cart)->map(function ($quantity, $pizzaId) {
                return [
                    'id' => $pizzaId,
                    'quantity' => $quantity
                ];
            })->values()
        ]);
    }
    
    /**
     * Debug method to check session
     */
    public function debug()
    {
        $cart = Session::get('cart', []);
        $sessionId = Session::getId();
        
        return response()->json([
            'session_id' => $sessionId,
            'cart' => $cart,
            'count' => array_sum($cart),
            'all_session_data' => Session::all()
        ]);
    }
}
