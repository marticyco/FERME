<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    // Afficher toutes les écritures de journal
    public function index()
    {
        $journalEntries = JournalEntry::with('journalLines.account')->get();
        return response()->json($journalEntries);
    }

    // Afficher une écriture de journal spécifique
    public function show($id)
    {
        $journalEntry = JournalEntry::with('journalLines.account')->find($id);

        if (!$journalEntry) {
            return response()->json(['message' => 'Écriture de journal non trouvée'], 404);
        }

        return response()->json($journalEntry);
    }

    // Créer une nouvelle écriture de journal
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'journalLines' => 'required|array|min:2',
            'journalLines.*.account_id' => 'required|exists:accounts,id',
            'journalLines.*.debit' => 'required|numeric|min:0',
            'journalLines.*.credit' => 'required|numeric|min:0',
        ]);

        // Commencer une transaction pour assurer l'intégrité des écritures
        \DB::beginTransaction();

        try {
            $journalEntry = JournalEntry::create([
                'date' => $request->date,
            ]);

            $total_debit = 0;
            $total_credit = 0;

            // Créer les lignes de journal
            foreach ($request->journalLines as $lineData) {
                $journalEntry->journalLines()->create([
                    'account_id' => $lineData['account_id'],
                    'debit' => $lineData['debit'],
                    'credit' => $lineData['credit'],
                ]);

                $total_debit += $lineData['debit'];
                $total_credit += $lineData['credit'];
            }

            // Vérifier que la somme des débits et crédits est égale
            if ($total_debit != $total_credit) {
                \DB::rollback();
                return response()->json(['message' => 'Les débits et crédits doivent être égaux'], 400);
            }

            \DB::commit();

            // Charger les lignes de journal associées
            $journalEntry->load('journalLines.account');
            return response()->json($journalEntry, 201);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

    // Mettre à jour une écriture de journal
    public function update(Request $request, $id)
    {
        $journalEntry = JournalEntry::find($id);

        if (!$journalEntry) {
            return response()->json(['message' => 'Écriture de journal non trouvée'], 404);
        }

        $request->validate([
            'date' => 'required|date',
            'journalLines' => 'required|array|min:2',
            'journalLines.*.account_id' => 'required|exists:accounts,id',
            'journalLines.*.debit' => 'required|numeric|min:0',
            'journalLines.*.credit' => 'required|numeric|min:0',
        ]);

        // Commencer une transaction pour la mise à jour
        \DB::beginTransaction();

        try {
            $journalEntry->update([
                'date' => $request->date,
            ]);

            // Supprimer les lignes existantes
            $journalEntry->journalLines()->delete();

            $total_debit = 0;
            $total_credit = 0;

            // Créer les nouvelles lignes de journal
            foreach ($request->journalLines as $lineData) {
                $journalEntry->journalLines()->create([
                    'account_id' => $lineData['account_id'],
                    'debit' => $lineData['debit'],
                    'credit' => $lineData['credit'],
                ]);

                $total_debit += $lineData['debit'];
                $total_credit += $lineData['credit'];
            }

            // Vérifier que la somme des débits et crédits est égale
            if ($total_debit != $total_credit) {
                \DB::rollback();
                return response()->json(['message' => 'Les débits et crédits doivent être égaux'], 400);
            }

            \DB::commit();

            // Charger les lignes de journal associées
            $journalEntry->load('journalLines.account');
            return response()->json($journalEntry);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

    // Supprimer une écriture de journal
    public function destroy($id)
    {
        $journalEntry = JournalEntry::find($id);

        if (!$journalEntry) {
            return response()->json(['message' => 'Écriture de journal non trouvée'], 404);
        }

        $journalEntry->delete();
        return response()->json(['message' => 'Écriture de journal supprimée avec succès']);
    }
}

