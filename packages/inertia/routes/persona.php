<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Persona Routes
|--------------------------------------------------------------------------
|
| Publishable route file for the Persona Inertia controller. The calling
| app owns the middleware group — put your real `auth` / `verified` /
| tenant-scope middleware here. Load it from routes/web.php:
|
|     require __DIR__ . '/persona.php';
|
| Every route keeps the `/persona` prefix and resolves the personable from
| `$request->user()` inside the controller (see `PersonaController::personable`).
*/

Route::middleware(['auth', 'verified'])->prefix('persona')->group(function () {
    // Profile
    Route::get('/', [PersonaController::class, 'show'])->name('persona.show');

    // Contacts
    Route::post('/contacts', [PersonaController::class, 'storeContact'])->name('persona.contacts.store');
    Route::patch('/contacts/{contact}/primary', [PersonaController::class, 'setPrimaryContact'])->name('persona.contacts.primary');
    Route::delete('/contacts/{contact}', [PersonaController::class, 'destroyContact'])->name('persona.contacts.destroy');

    // Addresses
    Route::post('/addresses', [PersonaController::class, 'storeAddress'])->name('persona.addresses.store');
    Route::patch('/addresses/{address}/primary', [PersonaController::class, 'setPrimaryAddress'])->name('persona.addresses.primary');
    Route::delete('/addresses/{address}', [PersonaController::class, 'destroyAddress'])->name('persona.addresses.destroy');

    // Social accounts
    Route::post('/social-accounts', [PersonaController::class, 'storeSocialAccount'])->name('persona.socials.store');
    Route::patch('/social-accounts/{socialAccount}/primary', [PersonaController::class, 'setPrimarySocialAccount'])->name('persona.socials.primary');
    Route::delete('/social-accounts/{socialAccount}', [PersonaController::class, 'destroySocialAccount'])->name('persona.socials.destroy');

    // Documents
    Route::post('/documents', [PersonaController::class, 'storeDocument'])->name('persona.documents.store');
    Route::post('/documents/{document}/files', [PersonaController::class, 'attachDocumentFile'])->name('persona.documents.attach');
    Route::delete('/documents/{document}', [PersonaController::class, 'destroyDocument'])->name('persona.documents.destroy');

    // Physical attributes & legal details (single upsert per entity)
    Route::put('/physical-attributes', [PersonaController::class, 'updatePhysicalAttributes'])->name('persona.physical-attributes.update');
    Route::put('/legal-details', [PersonaController::class, 'updateLegalDetails'])->name('persona.legal-details.update');

    // Relationships
    Route::post('/relationships', [PersonaController::class, 'storeRelationship'])->name('persona.relationships.store');
    Route::delete('/relationships/{relationship}', [PersonaController::class, 'destroyRelationship'])->name('persona.relationships.destroy');
});