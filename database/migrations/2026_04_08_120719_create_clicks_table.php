<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('clicks', function (Blueprint $table) {
        $table->id();
        $table->string('ip');
        $table->text('device');
        $table->timestamp('clicked_at');
        $table->timestamps();
        $table->string('city')->nullable()->after('device');
        $table->string('country')->nullable()->after('city');
        $table->string('latitude')->nullable()->after('country');
        $table->string('longitude')->nullable()->after('latitude');
         $table->string('isp')->nullable()->after('device');
      
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
