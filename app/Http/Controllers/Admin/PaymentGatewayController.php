<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    /**
     * Display the payment gateways management page.
     */
    public function index()
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();
        return view('admin.payments.index', compact('gateways'));
    }

    /**
     * Store a new payment gateway.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->name, '_');

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (PaymentGateway::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '_' . $counter;
            $counter++;
        }

        $gateway = PaymentGateway::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon ?? 'fas fa-credit-card',
            'icon_color' => $request->icon_color ?? '#000000',
            'description' => $request->description,
            'credentials' => [],
            'is_active' => false,
            'is_sandbox' => true,
            'sort_order' => PaymentGateway::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', "Payment gateway '{$gateway->name}' has been deployed successfully.");
    }

    /**
     * Update gateway credentials and settings.
     */
    public function update(Request $request, PaymentGateway $gateway)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $credentials = [];
        $fields = PaymentGateway::getDefaultFields($gateway->slug);

        foreach ($fields as $field) {
            $value = $request->input('credentials.' . $field['key'], '');
            // If the field is password type and value is empty, keep existing value
            if ($field['type'] === 'password' && empty($value)) {
                $value = $gateway->getCredential($field['key'], '');
            }
            $credentials[$field['key']] = $value;
        }

        // Also handle any custom fields submitted
        if ($request->has('custom_credentials')) {
            foreach ($request->input('custom_credentials', []) as $key => $value) {
                if (!empty($key)) {
                    $credentials[$key] = $value;
                }
            }
        }

        $gateway->update([
            'name' => $request->name,
            'credentials' => $credentials,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
            'is_sandbox' => $request->has('is_sandbox') ? $request->boolean('is_sandbox') : false,
            'description' => $request->input('description', $gateway->description),
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', "'{$gateway->name}' gateway credentials have been updated successfully.");
    }

    /**
     * Toggle gateway active status.
     */
    public function toggle(PaymentGateway $gateway)
    {
        $gateway->update(['is_active' => !$gateway->is_active]);

        $status = $gateway->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.payments.index')
            ->with('success', "'{$gateway->name}' has been {$status}.");
    }

    /**
     * Delete a payment gateway.
     */
    public function destroy(PaymentGateway $gateway)
    {
        $name = $gateway->name;
        $gateway->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', "'{$name}' gateway has been permanently removed.");
    }
}
