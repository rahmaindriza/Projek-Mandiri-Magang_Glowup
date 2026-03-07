<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight italic">Ulasan Pelanggan ✨</h2>
    </x-slot>

    <div class="py-12 bg-pink-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-white">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-pink-600 text-[10px] font-black uppercase tracking-widest border-b border-pink-50">
                            <th class="px-4 py-4">Produk</th>
                            <th class="px-4 py-4">Pelanggan</th>
                            <th class="px-4 py-4">Rating</th>
                            <th class="px-4 py-4">Komentar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($reviews as $review)
                        <tr class="hover:bg-pink-50/20 transition">
                            <td class="px-4 py-6 font-bold text-gray-800 text-sm">{{ $review->product->name }}</td>
                            <td class="px-4 py-6 text-xs text-gray-500">{{ $review->user->name }}</td>
                            <td class="px-4 py-6 text-yellow-400 font-bold italic">
                                {{ str_repeat('⭐', $review->rating) }}
                            </td>
                            <td class="px-4 py-6 text-xs italic text-gray-600">"{{ $review->comment }}"</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
