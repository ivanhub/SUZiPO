<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ref_dateofagreementeducation', function (Blueprint $table) {
            $table->integer('id');
            $table->string('agreementnumber', 100)->nullable();
            $table->date('agreementdate')->nullable();
            $table->string('kodsap', 10)->nullable();
            $table->string('usercreate', 50)->nullable();
            $table->string('useredit', 50)->nullable();
            $table->timestamp('editdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_dateofagreementeducation');
    }
};
