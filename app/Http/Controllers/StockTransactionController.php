<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActivityLogger;

class StockTransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $accessibleIds = $user->getAccessibleProjectIds();
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $transactionTypes = [
            // Regular Materials
            'GRV' => '📥 GRV - Goods Received Voucher',
            'ISTRV' => '📥 ISTRV - Inter Store Transfer Receiving',
            
            // Fixed Assets
            'FARV' => '🏗️ FARV - Fixed Asset Receiving (ISFATV/ISFATRV)',
            
            // Used Materials
            'UMTRV' => '♻️ UMTRV - Used Material Transfer Receiving (UMTR/UMTRV)',
            
            // Other Receiving
            'SRV' => '🔄 SRV - Store Return Voucher',
            'TTRV' => '📥 TTRV - Temporary Transfer Receiving',
            'FGRV' => '🏭 FGRV - Finished Good Receiving',
            'FRV' => '⛽ FRV - Fuel Receiving Voucher',
            'BEGINNING_BALANCE' => '📊 BEGINNING_BALANCE - Opening Stock',
            
            // Issue/Transfer Out
            'SIV' => '📤 SIV - Store Issue Voucher',
            'TRANSFER_OUT' => '📤 TRANSFER_OUT - Transfer Out',
            'FIV' => '⛽ FIV - Fuel Issue Voucher',
            'UMIV' => '♻️ UMIV - Used Material Issue Voucher',
            'UMTV' => '♻️ UMTV - Used Material Transfer Voucher',
            
            // Return
            'STORE_RETURN' => '🔄 STORE_RETURN - Store Return',
        ];

        return view('transactions.index', compact('locations', 'transactionTypes'));
    }

    public function create()
    {
        return redirect()->route('transactions.index');
    }

    public function getData(Request $request)
    {
        $user = auth()->user();
        $transactions = StockTransaction::with(['item', 'fromLocation', 'toLocation', 'creator'])
            ->select('stock_transactions.*');

        if (!$user->isHighLevelRole()) {
            $accessibleIds = $user->getAccessibleProjectIds();
            $transactions->where(function($q) use ($accessibleIds) {
                $q->whereIn('from_location_id', $accessibleIds)
                  ->orWhereIn('to_location_id', $accessibleIds);
            });
        }

        if ($request->date_from) $transactions->whereDate('transaction_date', '>=', $request->date_from);
        if ($request->date_to) $transactions->whereDate('transaction_date', '<=', $request->date_to);
        if ($request->transaction_type) $transactions->where('transaction_type', $request->transaction_type);
        if ($request->location_id) {
            $transactions->where(function($q) use ($request) {
                $q->where('from_location_id', $request->location_id)
                  ->orWhere('to_location_id', $request->location_id);
            });
        }

        return DataTables::of($transactions)
            ->addColumn('item_name', fn($t) => $t->item->name ?? 'N/A')
            ->addColumn('item_unit', fn($t) => $t->item->unit ?? '')
            ->addColumn('from_location', fn($t) => $t->fromLocation->name ?? '-')
            ->addColumn('to_location', fn($t) => $t->toLocation->name ?? '-')
            ->addColumn('type_badge', function($t) {
                $badges = [
                    'GRV' => 'success', 'ISTRV' => 'info', 'SIV' => 'warning',
                    'TRANSFER_OUT' => 'danger', 'STORE_RETURN' => 'primary',
                    'BEGINNING_BALANCE' => 'secondary',
                    'SRV' => 'primary', 'FIV' => 'warning', 'UMIV' => 'secondary',
                    'TTRV' => 'info', 'FARV' => 'dark', 'UMTV' => 'secondary',
                    'UMTRV' => 'info', 'FGRV' => 'success', 'FRV' => 'warning',
                ];
                $badge = $badges[$t->transaction_type] ?? 'secondary';
                return '<span class="badge bg-' . $badge . '">' . $t->transaction_type . '</span>';
            })
            ->addColumn('voucher_out', function($t) {
                // Transfer Out / Issue voucher number
                return $t->reference_number ?? '-';
            })
            ->addColumn('voucher_in', function($t) {
                // Receiving voucher number
                return $t->document_number ?? '-';
            })
            ->rawColumns(['type_badge'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:GRV,ISTRV,SIV,TRANSFER_OUT,STORE_RETURN,BEGINNING_BALANCE,SRV,FIV,UMIV,TTRV,FARV,UMTV,UMTRV,FGRV,FRV',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $item = Item::find($request->item_id);
        $type = $request->transaction_type;

        // IN types
        $inTypes = ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE', 'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'];
        // OUT types
        $outTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];

        if (in_array($type, $outTypes)) {
            if (!$request->from_location_id) {
                return response()->json(['success' => false, 'message' => 'From Location is required'], 422);
            }
            $currentStock = $item->getCurrentStock($request->from_location_id);
            if ($currentStock < $request->quantity) {
                return response()->json(['success' => false, 'message' => "Insufficient stock! Current: {$currentStock} {$item->unit}"], 422);
            }
        }

        if (in_array($type, $inTypes)) {
            if (!$request->to_location_id) {
                return response()->json(['success' => false, 'message' => 'To Location is required'], 422);
            }
        }

        $transaction = StockTransaction::create([
            'transaction_date' => $request->transaction_date,
            'transaction_type' => $type,
            'item_id' => $request->item_id,
            'from_location_id' => $request->from_location_id,
            'to_location_id' => $request->to_location_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'document_number' => $request->document_number,
            'remarks' => $request->remarks,
            'created_by' => $user->id,
        ]);

        ActivityLogger::log('CREATE', "Transaction {$type} created: {$item->name}", 'TRANSACTION', $transaction->id, $transaction->transaction_number, 'Transactions');

        return response()->json(['success' => true, 'message' => 'Transaction created successfully!']);
    }

    public function show($id)
    {
        return response()->json(
            StockTransaction::with(['item', 'fromLocation', 'toLocation', 'creator'])->findOrFail($id)
        );
    }

    public function edit($id)
    {
        return response()->json(
            StockTransaction::with(['item', 'fromLocation', 'toLocation'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $transaction = StockTransaction::findOrFail($id);
        
        $transaction->update([
            'transaction_date' => $request->transaction_date,
            'transaction_type' => $request->transaction_type,
            'item_id' => $request->item_id,
            'from_location_id' => $request->from_location_id,
            'to_location_id' => $request->to_location_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'document_number' => $request->document_number,
            'remarks' => $request->remarks,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaction updated successfully!']);
    }

    public function destroy($id)
    {
        StockTransaction::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaction deleted successfully!']);
    }

    public function searchLocations(Request $request)
    {
        $search = $request->get('q');
        $user = auth()->user();
        
        $query = Location::where('is_active', true);
        
        if (!$user->isHighLevelRole()) {
            $accessibleIds = $user->getAccessibleProjectIds();
            $query->whereIn('id', $accessibleIds);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $locations = $query->orderBy('code')->limit(30)->get(['id', 'code', 'name', 'type']);
        return response()->json($locations);
    }
}
