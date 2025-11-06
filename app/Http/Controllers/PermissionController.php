<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\GroupePermission;
use App\Models\Permission;
use App\Models\PermissionUser;
use Illuminate\Http\Request;
use Exception;

class PermissionController extends Controller
{
    //
    public function paramettre()
    {
        //
        try {
            $GroupePermissions = GroupePermission::orderBy('created_at', 'desc')->get();
            $Tab_permissions = [];

            foreach ($GroupePermissions as $GroupePermission) {
                $Tab_permissions[] = [
                    'GroupePermission' => $GroupePermission,
                    'Permissions' => Permission::where('groupe_id', $GroupePermission->id)->get(),
                    'PermissionUsers' => PermissionUser::whereIn('permission_id', Permission::where('groupe_id', $GroupePermission->id)->pluck('id'))->get()
                ];
            }
            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return view('components.configuration.parametre-droits', compact('Tab_permissions'));
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function permissions(Request $request)
    {
        //
        try {
            if ($request->nom) {
                $groupe = new GroupePermission();
                $groupe->nom = $request->nom;
                $groupe->module_id = $request->module_id;
                $groupe->description = $request->description;
                $groupe->save();
            }
            $entreprises = Entreprise::All();
            foreach ($request->libelles as $libelle) {
                foreach ($entreprises as $entreprise) {
                    $permission = new Permission();
                    $permission->libelle = $libelle;
                    $permission->entreprise_id = $entreprise->id;
                    $permission->groupe_id = $groupe->id ;
                    $permission->save();
                }
            }
            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Permissions enregistrées avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function groupe_permissions(Request $request, $id)
    {
        //
        try {

            $GroupePermission = GroupePermission::findOrFail($id);
            $GroupePermission->nom = $request->nom;
            $GroupePermission->save();

            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Groupe modifiée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function modif_permissions(Request $request, $id)
    {
        //
        try {

            $permission = Permission::findOrFail($id);
            $permission->libelle = $request->libelle;
            $permission->save();

            // Transmission des données à la vue (exemple: ressources/views/entreprises/index.blade.php)
            return redirect()->back()->with('success', 'Permission modifiée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger en renvoyant un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function supprimer_groupe(string $id)
    {
        //
        try {
            // Recherche du groupe de permissions par son ID
            $groupe = GroupePermission::findOrFail($id);

            // Suppression des permissions associées à ce groupe
            Permission::where('groupe_id', $groupe->id)->delete();

            // Suppression du groupe de permissions
            $groupe->delete();

            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Groupe supprimé avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function supprimer_permission(string $id)
    {
        //
        try {
            // Recherche du groupe de permissions par son ID
            $groupe = Permission::findOrFail($id);
            // Suppression du groupe de permissions
            $groupe->delete();
            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Permission supprimée avec succès.');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function permission(Request $request)
    {
        // 1. Supprimer toutes les permissions existantes de l'utilisateur
        PermissionUser::where('user_id', $request->user_id)->delete();

        // 2. Réinsérer uniquement les permissions cochées
        if ($request->has('permission')) {
            foreach ($request->permission as $permission_id) {
                PermissionUser::create([
                    'user_id' => $request->user_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Permissions attribuées avec succès !');
    }


    /**
     * Show the form for creating a new resource.
     */
}
