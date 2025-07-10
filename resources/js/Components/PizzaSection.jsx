import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { CartProvider } from '../context/CartContext';
import PizzaCard from './PizzaCard';
import FloatingCart from './FloatingCart';

export default function PizzaSection() {
    const [pizzas, setPizzas] = useState([]);

    useEffect(() => {
        // Get pizza data from window object or fetch from API
        if (window.pizzaData) {
            setPizzas(window.pizzaData);
        } else {
            // Fallback: fetch from API
            fetch('/api/pizzas')
                .then(response => response.json())
                .then(data => setPizzas(data))
                .catch(error => console.error('Error fetching pizzas:', error));
        }
    }, []);

    return (
        <CartProvider>
            <div className="text-center w-full mb-8">
                <motion.h1 
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6 }}
                    className="text-5xl sm:text-6xl text-red-600 font-bold mb-4"
                >
                    Nuestras Pizzas
                </motion.h1>
                <motion.p 
                    initial={{ opacity: 0, y: 30 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6, delay: 0.2 }}
                    className="text-gray-600 text-lg"
                >
                    Selecciona tu pizza favorita
                </motion.p>
            </div>

            <div className="w-full py-4">
                <motion.div
                    initial={{ y: 100, opacity: 0 }}
                    animate={{ y: 0, opacity: 1 }}
                    transition={{ 
                        duration: 0.8, 
                        ease: [0.25, 0.46, 0.45, 0.94] 
                    }}
                    className="w-full max-w-4xl mx-auto"
                >
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 pb-20">
                        {pizzas.map((pizza, index) => (
                            <motion.div
                                key={pizza.id}
                                initial={{ y: 50, opacity: 0, scale: 0.9 }}
                                animate={{ y: 0, opacity: 1, scale: 1 }}
                                transition={{ 
                                    duration: 0.5, 
                                    delay: 0.2 + (index * 0.1),
                                    ease: "easeOut"
                                }}
                            >
                                <PizzaCard pizza={pizza} index={index} />
                            </motion.div>
                        ))}
                    </div>
                </motion.div>
            </div>

            <FloatingCart />
        </CartProvider>
    );
}
