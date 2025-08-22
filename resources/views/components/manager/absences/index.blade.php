{{-- resources/views/manager/absences/index.blade.php --}}

@extends('layouts.app') {{-- Adaptez à votre layout principal --}}

@section('content')
<div class="container">
    <h1>Demandes d'Absence à Valider</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Période</th>
                <th>Motif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendingAbsences as $absence)
                <tr>
                    <td>{{ $absence->user->name }}</td>
                    <td>{{ $absence->type }}</td>
                    <td>Du {{ $absence->start_datetime->format('d/m/Y') }} au {{ $absence->end_datetime->format('d/m/Y') }}</td>
                    <td>{{ $absence->reason }}</td>
                    <td>
                        <form action="{{ route('manager.absences.updateStatus', $absence) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="approuvé" class="btn btn-success btn-sm">Approuver</button>
                            <button type="submit" name="status" value="rejeté" class="btn btn-danger btn-sm">Rejeter</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune demande en attente de validation.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection