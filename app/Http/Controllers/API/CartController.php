<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Add product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        try {
            // Log incoming request for debugging purposes
            Log::info('Incoming request data:', $request->all());

            // Validate the incoming request data
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id', // Ensure the product exists
                'quantity' => 'required|integer|min:1', // Ensure the quantity is at least 1
            ]);

            // Check if the product already exists in the cart
            $cart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $validated['product_id'])
                        ->first();

            if ($cart) {
                // If product exists in the cart, update the quantity
                $cart->quantity += $validated['quantity'];
                $cart->save();
            } else {
                // If the product doesn't exist in the cart, create a new cart item
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                ]);
            }

            // Return a success response
            return response()->json(['message' => 'Product added to cart successfully!'], 201);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid data provided. Please check your input.'], 422);
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while adding the product to the cart.'], 500);
        }
    }

    /**
     * Get the current user's cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart()
    {
        try {
            // Get the user's cart, including related product information
            $cart = Cart::with('product')->where('user_id', Auth::id())->get();

            // Return the cart items in the response
            return response()->json($cart);
        } catch (\Exception $e) {
            // Log errors if something goes wrong
            Log::error('Error fetching cart: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching the cart.'], 500);
        }
    }

    /**
     * Remove a product from the user's cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart($id)
    {
        try {
            // Find the cart item by ID and check if it belongs to the logged-in user
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                return response()->json(['message' => 'Cart item not found'], 404);
            }

            // Delete the cart item
            $cart->delete();

            // Return success response
            return response()->json(['message' => 'Product removed from cart successfully!']);
        } catch (\Exception $e) {
            // Log errors if something goes wrong
            Log::error('Error removing from cart: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while removing the product from the cart.'], 500);
        }
    }
}
