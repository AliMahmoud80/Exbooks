<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Preview;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExchangeResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class ExchangesController extends Controller
{
  private $per_page = 15;

  public function __construct()
  {
    $this->middleware('auth:sanctum')->only('store', 'edit', 'destroy');
  }

  public function index()
  {
    return ExchangeResource::collection(Exchange::orderByDesc('created_at')->paginate($this->per_page));
  }

  public function show($id)
  {
    try {
      $exchange = Exchange::findOrFail($id);
      return ExchangeResource::make($exchange);
    } catch (ModelNotFoundException $e) {
      return response()->json('Not Found', 404);
    } catch (\Exception $e) {
      return response()->json('Internal Error', 500);
    }
  }

  public function store(Request $request)
  {
    $valid = $request->validate([
      'data' => 'required|json',
      'preview[].*' => 'image|mimes:jpg,jpeg,png',
      'preview' => 'max:5'
    ]);

    if ($valid) {
      $data = json_decode($request['data'], true);

      $validated = Validator::make($data, [
        'wantedBooks' => 'array|required|min:1',
        'wantedBooks.*.name' => 'string|required|min:2',
        'ownedBooks' => 'array|required|min:1',
        'ownedBooks.*.name' => 'string|required|min:2',
        'description' => 'string',
        'country' => 'required|integer|exists:countries,id',
        'city' => 'required|integer|exists:cities,id',
      ], [
        'required' => 'Value is required'
      ])->validate();

      if ($validated) {
        DB::beginTransaction();

        // Create exchange.
        $exchange = Exchange::create([
          'user_id' => request()->user()->id,
          'description' => isset($validated['description']) ? $validated['description'] : null,
          'country_id' => $validated['country'],
          'city_id' => $validated['city'],
        ]);

        // Insert books.
        Book::insert($this->formatBook($validated['ownedBooks'], 'owned', $exchange->id));
        Book::insert($this->formatBook($validated['wantedBooks'], 'wanted', $exchange->id));

        // Handle images if exist.
        if ($request->hasFile('preview')) {
          $preview_imgs = $request->file('preview');

          foreach ($preview_imgs as $img) {
            $img_content = base64_encode(file_get_contents($img));

            $img = Preview::create([
              'exchange_id' => $exchange->id,
              'image' => $img_content,
            ]);
          }
        }

        DB::commit();

        return response()->json($exchange);
      }

      return response()->json(['success' => false], 500);
    }

    return response()->json(['success' => false], 500);
  }

  public function find($key, $page = 1)
  {
    $key = strtolower($key);
    $result = [];

    if (Cache::has("exchanges_search_$key")) {
      $result = Cache::get("exchanges_search_$key");
    } else {
      $query = DB::table('exchanges')
        ->leftJoin('countries', 'exchanges.country_id', '=', 'countries.id')
        ->leftJoin('cities', 'exchanges.city_id', '=', 'cities.id')
        ->leftJoin('books', 'exchanges.id', '=', 'exchange_id')
        ->where(DB::raw('LOWER(books.name)'), 'LIKE', '%' . $key . '%')
        ->orWhere(DB::raw('LOWER(cities.name)'), 'LIKE', '%' . $key . '%')
        ->orWhere(DB::raw('LOWER(countries.name)'), 'LIKE', '%' . $key . '%')
        ->orWhere(DB::raw('LOWER(exchanges.description)'), 'LIKE', '%' . $key . '%')
        ->distinct()
        ->orderBy('exchanges.created_at', 'desc')
        ->select('exchanges.*');

      $result = $query->get();

      Cache::put("exchanges_search_$key", $result, $seconds = 3 * 60);
    }

    $offset = ($page - 1) * $this->per_page;


    $total = $result->count();

    $page_count = $total / $this->per_page;
    $page_count = intval(ceil($total));

    $result =  ExchangeResource::collection($result);

    if ($page > $page_count) {
      return response()->json([]);
    }

    $result = collect($result)->slice($offset, $this->per_page)->values();

    $paginator = new LengthAwarePaginator($result, $total, $this->per_page, $page);

    return response()->json($paginator);
  }

  public function destroy($id)
  {
    $exchange = Exchange::find($id);

    $exchange_id = $exchange->id;

    if ($exchange->user_id === Auth::user()->id) {
      $exchange->delete();

      return response()->json(['deleted' => true, 'exchange_id' => $exchange_id]);
    }

    return response()->json(['deleted' => false]);
  }

  private function formatBook(array $Book, string $status, int $exchange_id): array
  {
    $formated = array_map(function ($book) use ($status, $exchange_id) {
      return [
        'exchange_id' => $exchange_id,
        'name' => $book['name'],
        'status' => $status,
        'created_at' => now(),
        'updated_at' => now()
      ];
    }, $Book);

    return $formated;
  }
}
