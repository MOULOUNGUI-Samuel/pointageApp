<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Liste des contrats
     */
    public function index()
    {
        // Vérifier et mettre à jour automatiquement les contrats expirés
        $entrepriseId = session('entreprise_id');
        $expiredCount = $this->contractService->checkAndUpdateExpiredContracts($entrepriseId);

        // Message optionnel si des contrats ont été mis à jour
        if ($expiredCount > 0) {
            session()->flash('info', "$expiredCount contrat(s) expiré(s) ont été automatiquement mis à jour.");
        }

        return view('contracts.index');
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $userId = $request->query('user_id');
        return view('contracts.create', compact('userId'));
    }

    /**
     * Afficher un contrat
     */
    public function show($id)
    {
        $contract = Contract::with(['user', 'entreprise', 'createdBy', 'updatedBy', 'parentContract', 'renewedContracts'])
            ->findOrFail($id);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);

        if (!$contract->estModifiable()) {
            return redirect()->route('contracts.show', $id)
                ->with('error', 'Ce contrat ne peut plus être modifié.');
        }

        return view('contracts.edit', compact('contract'));
    }

    /**
     * Formulaire de renouvellement
     */
    public function renew($id)
    {
        $contract = Contract::findOrFail($id);

        if (!$contract->estRenouvelable()) {
            return redirect()->route('contracts.show', $id)
                ->with('error', 'Ce contrat ne peut pas être renouvelé pour le moment.');
        }

        return view('contracts.renew', compact('contract'));
    }

    /**
     * Suspendre un contrat
     */
    public function suspend(Request $request, $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $this->contractService->suspendContract($contract, auth()->user(), $request->input('comment'));

            return redirect()->route('contracts.show', $id)
                ->with('success', 'Contrat suspendu avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Réactiver un contrat
     */
    public function reactivate(Request $request, $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $this->contractService->reactivateContract($contract, auth()->user(), $request->input('comment'));

            return redirect()->route('contracts.show', $id)
                ->with('success', 'Contrat réactivé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Terminer un contrat
     */
    public function terminate(Request $request, $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $this->contractService->terminateContract($contract, auth()->user(), $request->input('comment'));

            return redirect()->route('contracts.show', $id)
                ->with('success', 'Contrat terminé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Résilier un contrat (terminaison anticipée)
     */
    public function terminateEarly(Request $request, $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $this->contractService->terminateContractEarly($contract, auth()->user(), $request->input('comment'));

            return redirect()->route('contracts.show', $id)
                ->with('success', 'Contrat résilié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
