<x-guest-layout>
    <h1>Products index</h1>

    @auth
        <a href="">Create</a>
    @endauth

    @forelse($products as $product)
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->type }}</p>
        @auth
            <button>Buy Product</button>
        @endauth
    @empty
        <p>No products</p>
    @endforelse


</x-guest-layout>
