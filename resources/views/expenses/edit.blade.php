@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <span>Edit expense</span>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-light">
                        Back
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('expenses.update', $expense) }}">
                        @csrf
                        @method('PUT')

                        {{-- Amount --}}
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="amount"
                                id="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount', $expense->amount) }}"
                                placeholder="Ej: 1250.50"
                                required
                            >
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Spent at --}}
                        <div class="mb-3">
                            <label for="spent_at" class="form-label">Date</label>
                            <input
                                type="date"
                                name="spent_at"
                                id="spent_at"
                                class="form-control @error('spent_at') is-invalid @enderror"
                                value="{{ old('spent_at', $expense->spent_at) }}"
                                required
                            >
                            @error('spent_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input
                                type="text"
                                name="description"
                                id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                value="{{ old('description', $expense->description) }}"
                                placeholder="Optional"
                                maxlength="255"
                            >
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-outline-info">
                                Update
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
