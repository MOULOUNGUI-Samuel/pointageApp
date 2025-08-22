{{-- resources/views/absences/index.blade.php --}}

@extends('layouts.master2')
@section('content2')
<div class="container">
    <h1>Mes Demandes d'Absence</h1>
    
    <a href="{{ route('absencecreate') }}" class="btn btn-primary mb-3">Faire une nouvelle demande</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absences as $absence)
                <tr>
                    <td>{{ $absence->type }}</td>
                    <td>{{ $absence->start_datetime->format('d/m/Y H:i') }}</td>
                    <td>{{ $absence->end_datetime->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge 
                            @if($absence->status == 'approuvé') bg-success 
                            @elseif($absence->status == 'rejeté') bg-danger 
                            @else bg-warning @endif">
                            {{ ucfirst($absence->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Vous n'avez aucune demande d'absence.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection