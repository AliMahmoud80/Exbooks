<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\Book;
use App\Models\Preview;
use Carbon\Carbon;

class ExchangeResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => User::find($this->user_id),
            'wantedBooks' => Book::where('exchange_id', $this->id)->where('status', 'wanted')->get(),
            'ownedBooks' => Book::where('exchange_id', $this->id)->where('status', 'owned')->get(),
            'description' => isset($this->description) ? $this->description : null,
            // Previews hidden when fetching all exchanges on home page.
            'previews' => $this->when(!(\str_ends_with($request->url(), '/api/exchange')) && !(\str_ends_with($request->url(), '/api/user/exchanges')), Preview::where('exchange_id', $this->id)->get()),
            'city' => City::find($this->city_id)['name'],
            'country' => Country::find($this->country_id)['name'],
            'date' => new Carbon($this->created_at)
        ];
    }
}
