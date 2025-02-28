@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-light text-gray-800 mb-6">Edit Transaction</h2>

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full p-2 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <div class="flex items-center space-x-2">
                            <select name="category" id="categorySelect" class="w-full p-2 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ $transaction->category === $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button" id="addCategoryBtn" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        <div id="newCategoryInput" class="hidden mt-2">
                            <input type="text" id="newCategory" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Enter new category name">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" value="{{ $transaction->amount }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">{{ $transaction->description }}</textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600">
                    Update Transaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('categorySelect');
        const newCategoryInput = document.getElementById('newCategoryInput');
        const newCategoryField = document.getElementById('newCategory');
        const addCategoryBtn = document.getElementById('addCategoryBtn');

        addCategoryBtn.addEventListener('click', function() {
            newCategoryInput.classList.remove('hidden');
            categorySelect.disabled = true;
            newCategoryField.focus();
        });

        newCategoryField.addEventListener('blur', function() {
            if (this.value) {
                fetch('/categories', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            name: this.value
                        })
                    })
                    .then(response => response.json())
                    .then(category => {
                        const option = new Option(category.name, category.name);
                        categorySelect.add(option, categorySelect.length);
                        categorySelect.value = category.name;
                        categorySelect.disabled = false;
                        newCategoryInput.classList.add('hidden');
                    });
            } else {
                categorySelect.disabled = false;
            }
        });
    });
</script>
@endsection