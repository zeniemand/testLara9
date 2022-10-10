<x-guest-layout>
    <h1>Products index</h1>

    @auth
        @if(auth()->user()->is_admin)
            <a href="/products/create">Create</a>
        @endif
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
