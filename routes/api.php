use App\Http\Controllers\Api\TireSearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('tires/search')->group(function () {
    Route::get('by-vehicle', [TireSearchController::class, 'byVehicle']);
    Route::get('by-size', [TireSearchController::class, 'bySize']);
});
