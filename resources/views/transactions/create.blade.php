@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-light text-gray-800 mb-6">Add Transaction</h2>

        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full p-2 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <div class="flex items-center space-x-2">
                            <select name="category_id" id="categorySelect" class="w-full p-2 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <button type="button" id="addCategoryBtn" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>

                        <div id="newCategoryInput" class="hidden mt-2">
                            <input type="text"
                                name="newCategory"
                                id="newCategory"
                                class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Enter new category name">

                            <div id="categoryTypeDiv">
                                <label for="categoryType" class="block text-sm font-medium text-gray-700">Category Type</label>
                                <select id="categoryType" name="category_type" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="needs">Needs</option>
                                    <option value="wants">Wants</option>
                                    <option value="savings">Savings</option>
                                </select>
                            </div>

                            <button type="button" id="saveCategoryBtn" class="w-full mt-2 bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600">
                                Save Category
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600">
                    Add Transaction
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
        const saveCategoryBtn = document.getElementById('saveCategoryBtn');
        const typeSelect = document.querySelector('select[name="type"]');
        const categoryTypeSelect = document.getElementById('categoryType');
        const categoryTypeDiv = document.getElementById('categoryTypeDiv');


        function toggleCategoryType() {
            if (typeSelect.value === 'income') {
                categoryTypeDiv.classList.add('hidden');
                categoryTypeSelect.removeAttribute('required');
            } else {
                categoryTypeDiv.classList.remove('hidden');
                categoryTypeSelect.setAttribute('required', 'required');
            }
        }

        toggleCategoryType();
        typeSelect.addEventListener('change', toggleCategoryType);
        addCategoryBtn.addEventListener('click', toggleCategoryType);


        addCategoryBtn.addEventListener('click', function() {
            newCategoryInput.classList.remove('hidden');
            categorySelect.value = '';
            categorySelect.disabled = true;
            newCategoryField.focus();
        });

        saveCategoryBtn.addEventListener('click', function() {
            if (newCategoryField.value) {
                const categoryData = {
                    name: newCategoryField.value,
                    type: typeSelect.value === 'income' ? 'income' : categoryTypeSelect.value,
                };

                console.log('Sending category data:', categoryData);

                fetch('/categories', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(categoryData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(category => {
                        console.log('Category created:', category);
                        const option = new Option(category.name, category.id);
                        categorySelect.add(option, categorySelect.length);
                        categorySelect.value = category.id;
                        categorySelect.disabled = false;
                        newCategoryInput.classList.add('hidden');
                        newCategoryField.value = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to create category. Please try again.');
                    });
            }
        });
    });
</script>
@endsection