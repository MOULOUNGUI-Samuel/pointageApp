{{-- resources/views/absences/create.blade.php --}}

@extends('layouts.master2')
@section('content2')
<div class="container">
    <h1>Nouvelle Demande d'Absence</h1>

    <form action="{{ route('absencestore') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Type d'absence</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="congé_payé">Congé payé</option>
                <option value="maladie">Maladie</option>
                <option value="RTT">RTT</option>
                <option value="autre">Autre</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="start_datetime" class="form-label">Début de l'absence</label>
            <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror" value="{{ old('start_datetime') }}">
            @error('start_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="end_datetime" class="form-label">Fin de l'absence</label>
            <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror" value="{{ old('end_datetime') }}">
            @error('end_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Motif</label>
            <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror">{{ old('reason') }}</textarea>
            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Soumettre la demande</button>
    </form>
</div>
@endsection