@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white p-4 shadow sm:rounded-lg">
        <h2 class="text-xl font-semibold">Distribution Details</h2>

        <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
                <strong>Disbursement:</strong>
                <div>{{ $pension_distribution->disbursement_date->format('M d, Y') }}</div>
            </div>
            <div>
                <strong>Amount:</strong>
                <div>₱{{ number_format($pension_distribution->amount,2) }}</div>
            </div>
            <div class="col-span-2">
                <strong>Recipient:</strong>
                <div>
                    @if($pension_distribution->seniorCitizen)
                        {{ $pension_distribution->seniorCitizen->getFormattedDisplayName() }}
                    @else
                        <em>Missing record</em>
                    @endif
                </div>
            </div>
            <div class="col-span-2">
                <strong>Status:</strong>
                <div>{{ ucfirst($pension_distribution->status) }} @if($pension_distribution->claimed_at) — claimed at {{ $pension_distribution->claimed_at->format('M d, Y H:i') }} @endif</div>
            </div>
        </div>

        @if($pension_distribution->status === 'unclaimed')
            <hr class="my-4">
            <h3 id="authorize" class="text-lg font-medium">Record Authorized Representative / Mark Claimed</h3>
            <form action="{{ route('pension-distributions.claim', $pension_distribution) }}" method="post" class="mt-3 space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Representative Name</label>
                    <input type="text" name="authorized_rep_name" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium">Relationship</label>
                    <input type="text" name="authorized_rep_relationship" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium">Contact</label>
                    <input type="text" name="authorized_rep_contact" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('spisc.index') }}" class="mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Mark Claimed</button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
