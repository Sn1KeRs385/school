<?php

namespace Tests;

use App\Consts\OrderStatuses;
use App\Consts\MediaCollectionNames;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait EssentialTestingData
{
    protected $user;
    protected $resident;
    protected $apartment;
    protected $orders;
    protected $settlement;
    protected $tidings;

    public function createUser(string $state){

        $this->user = factory(\App\User::class)
            ->state($state)
            ->create();   
    }

    public function createSettlement(){
        $this->settlement = factory(\App\AdminModels\Settlement::class)
            ->create();
    }

    public function createResident(){

        $this->resident = factory(\App\AdminModels\Resident::class)
            ->create([
                'user_id' => $this->user->id
            ]);
            
        return $this->resident;    
    }

    public function createApartment(){

        $this->apartment = factory(\App\AdminModels\Apartment::class)
            ->create();

        $this->resident->apartments()
            ->attach($this->apartment->id);  

        return $this->apartment;    
            
    }

    public function createOrders(){

        $this->orders = factory(\App\AdminModels\Order::class, 3)->create([
            'apartment_id' => $this->apartment->id,
        ]);

        $this->orders->each(function($order){

            factory(\App\AdminModels\OrderStatusHistory::class)
                ->create([
                    'order_id' => $order->id
                ]);  
        });

        return $this->orders;
    }

    public function createTidings(){

        $this->tidings = factory(\App\AdminModels\Tiding::class, 5)
            ->create();

    }

    public function createAndCheckTemporaryFile()
    {
        $temporary_file = factory(\App\TemporaryFile::class)
            ->create([
                'collection_name' => MediaCollectionNames::ORDERS_IMG,
                'is_uploaded' => 1
            ]);

        Storage::fake('local');
        UploadedFile::fake()
            ->image("{$temporary_file->id}.original")
            ->size(35)
            ->storeAs('', "{$temporary_file->id}.original");

        Storage::disk('local')
            ->assertExists("{$temporary_file->id}.original");

        return $temporary_file;
    }

}