import { Head } from '@inertiajs/react';

export default function Layout({ children }) {
    return (
        <div className="min-h-screen bg-yellow-50">
            {children}
        </div>
    );
}
