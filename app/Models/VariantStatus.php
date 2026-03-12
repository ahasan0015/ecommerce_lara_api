<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantStatus extends Model
{
    use HasFactory;

    // টেবিলের নাম যদি 'variant_statuses' হয় তবে এটি ডিফাইন করার প্রয়োজন নেই, 
    // লারাভেল অটোমেটিক ধরে নেবে। তবে সুরক্ষার জন্য লিখে রাখা ভালো।
    protected $table = 'variant_statuses';

    protected $fillable = ['name'];

    // রিলেশনশিপ: একটি স্ট্যাটাসের আন্ডারে অনেকগুলো ভেরিয়েন্ট থাকতে পারে
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'status_id');
    }
}