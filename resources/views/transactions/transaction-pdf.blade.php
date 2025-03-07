<!DOCTYPE html>
<html>

<head>
    <title>Transactions Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }

        .income {
            color: #059669;
        }

        .expense {
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div>
        <h1>SaveSmart Transactions</h1>
        <p>Generated on {{ now()->format('M d, Y') }}</p>
    </div>

    <br>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->category->name }}</td>
                <td class="{{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                </td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Income: <span class="income">{{ number_format($total_income, 2) }} MAD</span></p>
        <p>Total Expenses: <span class="expense">{{ number_format($total_expense, 2) }} MAD</span></p>
        <p>Net: <span class="{{ ($total_income - $total_expense) >= 0 ? 'income' : 'expense' }}">
                {{ number_format($total_income - $total_expense, 2) }} MAD
            </span></p>
    </div>
</body>

</html>